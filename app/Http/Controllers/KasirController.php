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
        // Load transactions to use grand_total accessor (includes additional transactions)
        $cashTransactions = Transaction::query()
            ->where('status', 'completed')
            ->whereIn('payment_method', ['cash', 'tunai'])
            ->where('created_at', '>=', $session->created_at)
            ->whereNull('parent_transaction_id')
            ->get();

        $cashRevenue = $cashTransactions->sum(fn ($t) => $t->grand_total);

        $creditDp = $this->getCreditDpTotal($session);
        $cashIn = $this->getCashInTotal($session->id);
        $cashOut = $this->getCashOutTotal($session->id);

        return (float) $session->opening_amount + $cashRevenue + $creditDp + $cashIn - $cashOut;
    }

    public function index()
    {
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'eceran')
            ->latest()
            ->first();

        if (! $activeSession) {
            return view('kasir.closed', ['type' => 'eceran']);
        }

        // Load root transactions to use grand_total accessor (includes additional transactions)
        $cashTransactions = Transaction::where('status', 'completed')
            ->whereIn('payment_method', ['cash', 'tunai'])
            ->where('created_at', '>=', $activeSession->created_at)
            ->whereNull('parent_transaction_id')
            ->get();

        $cashRevenue = $cashTransactions->sum(fn ($t) => $t->grand_total);
        $expectedCash = $this->getExpectedCash($activeSession);

        return view('kasir.index', compact('activeSession', 'cashRevenue', 'expectedCash'));
    }

    public function session()
    {
        $activeSession = \App\Models\PosSession::with('user')
            ->where('status', 'open')
            ->where('type', 'eceran')
            ->latest()
            ->first();

        if (! $activeSession) {
            return view('kasir.session', [
                'activeSession' => null,
            ]);
        }

        // Load root transactions to use grand_total accessor
        $rootTransactions = Transaction::where('status', 'completed')
            ->where('created_at', '>=', $activeSession->created_at)
            ->whereNull('parent_transaction_id')
            ->get();

        $cashRevenue = $rootTransactions
            ->whereIn('payment_method', ['cash', 'tunai'])
            ->sum(fn ($t) => $t->grand_total);

        $creditDp = $this->getCreditDpTotal($activeSession);
        $cashIn = $this->getCashInTotal($activeSession->id);
        $cashOut = $this->getCashOutTotal($activeSession->id);

        $nonCashRevenue = $rootTransactions
            ->whereNotIn('payment_method', ['cash', 'tunai'])
            ->sum(fn ($t) => $t->grand_total);

        $totalRevenue = $rootTransactions->sum(fn ($t) => $t->grand_total);

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

        // Cek jika sudah ada sesi kasir eceran aktif
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'eceran')
            ->first();

        if ($activeSession) {
            return back()->with('error', 'Sesi kasir eceran sudah aktif.');
        }

        \App\Models\PosSession::create([
            'user_id' => Auth::id(),
            'type' => 'eceran',
            'opening_amount' => $request->opening_amount,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'status' => 'open',
        ]);

        return redirect()->route('kasir.index')->with('success', 'Sesi Kasir Eceran berhasil dibuka dengan Modal Awal: Rp '.number_format($request->opening_amount, 0, ',', '.'));
    }

    public function openSessionGrosir(Request $request)
    {
        if (! Auth::user() || Auth::user()->role !== 'supervisor') {
            return redirect()->route('kasir.grosir')->with('error', 'Hanya Supervisor yang dapat membuka sesi kasir.');
        }

        $request->validate([
            'opening_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Cek jika sudah ada sesi kasir grosir aktif
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'grosir')
            ->first();

        if ($activeSession) {
            return back()->with('error', 'Sesi kasir grosir sudah aktif.');
        }

        \App\Models\PosSession::create([
            'user_id' => Auth::id(),
            'type' => 'grosir',
            'opening_amount' => $request->opening_amount,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'status' => 'open',
        ]);

        return redirect()->route('kasir.grosir')->with('success', 'Sesi Kasir Grosir berhasil dibuka dengan Modal Awal: Rp '.number_format($request->opening_amount, 0, ',', '.'));
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
            ->where('type', 'eceran')
            ->latest()
            ->first();

        if (! $activeSession) {
            return back()->with('error', 'Tidak ada sesi kasir eceran yang sedang aktif.');
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

    public function closeSessionGrosir(Request $request)
    {
        if (! Auth::user() || Auth::user()->role !== 'supervisor') {
            return redirect()->route('kasir.grosir')->with('error', 'Hanya Supervisor yang dapat menutup sesi kasir.');
        }

        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'grosir')
            ->latest()
            ->first();

        if (! $activeSession) {
            return back()->with('error', 'Tidak ada sesi kasir grosir yang sedang aktif.');
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

        return redirect()->route('dashboard')->with('success', 'Sesi Kasir Grosir berhasil ditutup. Total kas: Rp '.number_format($actualCash, 0, ',', '.'));
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

        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'eceran')
            ->latest()
            ->first();
        if (! $activeSession) {
            return back()->with('error', 'Tidak ada sesi kasir eceran yang sedang aktif.');
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

    public function storeTransaksi(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Endpoint lama dinonaktifkan. Gunakan /kasir/eceran atau /kasir/grosir.',
        ], 410);
    }

    /**
     * Show form to add items to completed transaction
     */
    public function addItemsForm(Transaction $transaction)
    {
        // Only allow adding items to completed transactions
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'Hanya transaksi selesai yang bisa ditambahkan item.');
        }

        // Load transaction with details
        $transaction->load(['details.product', 'customer', 'additionalTransactions.details.product']);

        // Get products with unit conversions for autocomplete
        $productService = app(\App\Services\ProductSearchService::class);
        $products = \App\Models\Product::with(['unitConversions.unit', 'category', 'productStocks.warehouse'])
            ->orderBy('name')
            ->limit(50)
            ->get()
            ->map(fn($p) => $productService->formatProductEceran($p))
            ->values();

        $warehouses = \App\Models\Warehouse::where('active', true)->get(['id', 'name']);

        return view('kasir.add_items', compact('transaction', 'products', 'warehouses'));
    }

    /**
     * Store additional items to transaction
     */
    public function storeAdditionalItems(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'completed') {
            return response()->json(['success' => false, 'message' => 'Transaksi tidak valid.'], 422);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.warehouse_id' => 'required|exists:warehouses,id',
            'additional_payment' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,qris',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $totalAmount = 0;
            $resolvedItems = [];

            foreach ($request->items as $item) {
                $product = \App\Models\Product::find($item['product_id']);

                // Check stock
                $warehouseStock = \App\Models\ProductStock::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $item['warehouse_id'])
                    ->sum('stock');

                if ($warehouseStock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi. Tersedia: {$warehouseStock}");
                }

                $subtotal = round($item['price'] * $item['quantity'], 2);
                $totalAmount += $subtotal;

                $resolvedItems[] = [
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                    'product_name' => $product->name,
                ];
            }

            // Create additional transaction with its own payment method
            $additionalPayment = $request->input('additional_payment', 0);
            $paymentMethod = $request->input('payment_method', $transaction->payment_method);
            $paymentReference = $request->input('payment_reference');

            $additionalTransaction = Transaction::create([
                'user_id' => Auth::id(),
                'customer_id' => $transaction->customer_id,
                'total_amount' => $totalAmount,
                'paid_amount' => $additionalPayment,
                'change_amount' => max(0, $additionalPayment - $totalAmount),
                'payment_method' => $paymentMethod,
                'payment_reference' => $paymentReference,
                'status' => 'completed',
                'parent_transaction_id' => $transaction->id,
                'transaction_type' => 'additional',
                'additional_notes' => $request->notes ?? 'Tambahan item untuk ' . $transaction->invoice_number,
            ]);

            // Create transaction details and deduct stock
            foreach ($resolvedItems as $item) {
                TransactionDetail::create([
                    'transaction_id' => $additionalTransaction->id,
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Deduct stock
                $product = \App\Models\Product::find($item['product_id']);
                $product->stock -= $item['quantity'];
                $product->save();

                // Record stock movement
                \App\Models\StockMovement::create([
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'reference_type' => 'Transaction',
                    'reference_id' => $additionalTransaction->id,
                    'notes' => 'Tambahan item untuk transaksi #' . $transaction->invoice_number,
                    'created_by' => Auth::id(),
                ]);
            }

            // Create pick order for additional items
            $pickOrderService = app(\App\Services\PosPickOrderService::class);
            $pickOrder = $pickOrderService->createFromTransaction($additionalTransaction, 'eceran');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan!',
                'additional_transaction_id' => $additionalTransaction->id,
                'grand_total' => $transaction->grand_total,
                'pick_order' => $pickOrder ? $pickOrder->pick_number : null,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
