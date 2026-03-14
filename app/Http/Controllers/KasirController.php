<?php

namespace App\Http\Controllers;

use App\Models\PosCashMovement;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    private function normalizePaymentMethod(?string $method): string
    {
        $method = strtolower(trim((string) $method));

        return match ($method) {
            'tunai' => 'cash',
            'credit' => 'kredit',
            default => $method,
        };
    }

    private function getCashInTotal(int $posSessionId): float
    {
        return (float) PosCashMovement::query()
            ->where('pos_session_id', $posSessionId)
            ->where('type', 'in')
            ->sum('amount');
    }

    private function getCashOutTotal(int $posSessionId): float
    {
        return (float) PosCashMovement::query()
            ->where('pos_session_id', $posSessionId)
            ->where('type', 'out')
            ->sum('amount');
    }

    private function getCreditDpTotal(\App\Models\PosSession $session): float
    {
        return (float) Transaction::query()
            ->where('status', 'completed')
            ->where('payment_method', 'kredit')
            ->where('created_at', '>=', $session->created_at)
            ->sum('paid_amount');
    }

    private function getExpectedCash(\App\Models\PosSession $session): float
    {
        $cashRevenue = (float) Transaction::query()
            ->where('status', 'completed')
            ->whereIn('payment_method', ['cash', 'tunai'])
            ->where('created_at', '>=', $session->created_at)
            ->sum('total_amount');

        $creditDp = $this->getCreditDpTotal($session);
        $cashIn = $this->getCashInTotal($session->id);
        $cashOut = $this->getCashOutTotal($session->id);

        return (float) $session->opening_amount + $cashRevenue + $creditDp + $cashIn - $cashOut;
    }

    public function index()
    {
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->latest()
            ->first();

        if (! $activeSession) {
            return view('kasir.closed');
        }

        $cashRevenue = Transaction::where('status', 'completed')
            ->whereIn('payment_method', ['cash', 'tunai'])
            ->where('created_at', '>=', $activeSession->created_at)
            ->sum('total_amount');
        $expectedCash = $this->getExpectedCash($activeSession);

        return view('kasir.index', compact('activeSession', 'cashRevenue', 'expectedCash'));
    }

    public function session()
    {
        $activeSession = \App\Models\PosSession::with('user')
            ->where('status', 'open')
            ->latest()
            ->first();

        if (! $activeSession) {
            return view('kasir.session', [
                'activeSession' => null,
            ]);
        }

        $cashRevenue = Transaction::where('status', 'completed')
            ->whereIn('payment_method', ['cash', 'tunai'])
            ->where('created_at', '>=', $activeSession->created_at)
            ->sum('total_amount');

        $creditDp = $this->getCreditDpTotal($activeSession);
        $cashIn = $this->getCashInTotal($activeSession->id);
        $cashOut = $this->getCashOutTotal($activeSession->id);

        $nonCashRevenue = Transaction::where('status', 'completed')
            ->whereNotIn('payment_method', ['cash', 'tunai'])
            ->where('created_at', '>=', $activeSession->created_at)
            ->sum('total_amount');

        $totalRevenue = Transaction::where('status', 'completed')
            ->where('created_at', '>=', $activeSession->created_at)
            ->sum('total_amount');

        $cashTransactions = Transaction::where('status', 'completed')
            ->whereIn('payment_method', ['cash', 'tunai'])
            ->where('created_at', '>=', $activeSession->created_at)
            ->count();

        $totalTransactions = Transaction::where('status', 'completed')
            ->where('created_at', '>=', $activeSession->created_at)
            ->count();

        $expectedCash = $this->getExpectedCash($activeSession);

        $cashMovements = PosCashMovement::query()
            ->with('user')
            ->where('pos_session_id', $activeSession->id)
            ->latest()
            ->limit(25)
            ->get();

        return view('kasir.session', compact(
            'activeSession',
            'cashRevenue',
            'creditDp',
            'cashIn',
            'cashOut',
            'nonCashRevenue',
            'totalRevenue',
            'cashTransactions',
            'totalTransactions',
            'expectedCash',
            'cashMovements'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $paymentMethod = $this->normalizePaymentMethod($request->payment_method);

            // ── Validasi stok sebelum transaksi ──────────────────────
            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                if (! $product || $product->stock < $item['quantity']) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Stok produk "'.($product->name ?? 'ID:'.$item['product_id']).'" tidak mencukupi. Tersedia: '.($product->stock ?? 0),
                    ], 422);
                }
            }

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'change_amount' => max(0, $request->paid_amount - $request->total_amount),
                'payment_method' => $paymentMethod,
                'status' => 'completed',
            ]);

            foreach ($request->items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Kurangi stok global
                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);

                // Kurangi stok per-gudang (FIFO dari product_stocks)
                $this->deductWarehouseStock($item['product_id'], $item['quantity'], $transaction->id);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'change' => $transaction->change_amount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Kurangi stok di product_stocks secara FIFO.
     */
    private function deductWarehouseStock(int $productId, int $qty, int $transactionId): void
    {
        $remaining = $qty;

        $stocks = ProductStock::where('product_id', $productId)
            ->where('stock', '>', 0)
            ->orderBy('expired_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $stock) {
            if ($remaining <= 0) {
                break;
            }

            $deduct = min($stock->stock, $remaining);
            $stock->stock -= $deduct;
            $stock->save();

            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $stock->warehouse_id,
                'location_id' => $stock->location_id,
                'type' => 'out',
                'source_type' => 'pos_transaction',
                'reference_number' => 'TRX-'.$transactionId,
                'quantity' => $deduct,
                'balance' => $stock->stock,
                'notes' => '[POS] Transaksi #'.$transactionId,
                'user_id' => Auth::id(),
            ]);

            $remaining -= $deduct;
        }
    }

    public function openSession(Request $request)
    {
        if (! Auth::user() || Auth::user()->role !== 'supervisor') {
            return redirect()->route('kasir.index')->with('error', 'Hanya Supervisor yang dapat membuka sesi kasir.');
        }

        $request->validate([
            'opening_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Cek jika sudah ada sesi kasir aktif
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->first();

        if ($activeSession) {
            return back()->with('error', 'Sesi kasir sudah aktif.');
        }

        \App\Models\PosSession::create([
            'user_id' => Auth::id(),
            'opening_amount' => $request->opening_amount,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'status' => 'open',
        ]);

        return redirect()->route('kasir.index')->with('success', 'Sesi Kasir berhasil dibuka dengan Modal Awal: Rp '.number_format($request->opening_amount, 0, ',', '.'));
    }

    public function closeSession(Request $request)
    {
        if (! Auth::user() || Auth::user()->role !== 'supervisor') {
            return redirect()->route('kasir.index')->with('error', 'Hanya Supervisor yang dapat menutup sesi kasir.');
        }

        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->latest()
            ->first();

        if (! $activeSession) {
            return back()->with('error', 'Tidak ada sesi kasir yang sedang aktif.');
        }

        $expectedCash = $this->getExpectedCash($activeSession);
        $actualCash = (float) $request->actual_cash;
        $variance = $actualCash - $expectedCash;

        $activeSession->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closing_amount' => $actualCash,
            'expected_cash' => $expectedCash,
            'actual_cash' => $actualCash,
            'cash_variance' => $variance,
            'notes' => $request->notes ?: $activeSession->notes,
        ]);

        return redirect()->route('dashboard')->with('success', 'Sesi Kasir berhasil ditutup. Total kas: Rp '.number_format($actualCash, 0, ',', '.'));
    }

    public function addCashMovement(Request $request)
    {
        if (! Auth::user() || Auth::user()->role !== 'supervisor') {
            return redirect()->route('kasir.index')->with('error', 'Hanya Supervisor yang dapat mencatat cash in/out.');
        }

        $request->validate([
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:500',
        ]);

        $activeSession = \App\Models\PosSession::where('status', 'open')->latest()->first();
        if (! $activeSession) {
            return back()->with('error', 'Tidak ada sesi kasir yang sedang aktif.');
        }

        PosCashMovement::create([
            'pos_session_id' => $activeSession->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Cash '.($request->type === 'in' ? 'In' : 'Out').' berhasil dicatat.');
    }

    /**
     * Alias untuk store — dipanggil dari route kasir.transaksi.store
     */
    public function storeTransaksi(Request $request)
    {
        return $this->store($request);
    }
}
