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
        $query = Transaction::query()
            ->where('status', 'completed')
            ->where('payment_method', 'kredit')
            ->where('user_id', $session->user_id)
            ->where('created_at', '>=', $session->created_at);

        if ($session->type === 'eceran') {
            $query->whereIn('sale_type', ['eceran', 'grosir']);
        } else {
            $query->where('sale_type', $session->type);
        }

        return (float) $query->sum('paid_amount');
    }

    /**
     * Public wrapper for getExpectedCash — used by sidebar modal.
     */
    public static function calcExpectedCash(\App\Models\PosSession $session): float
    {
        $instance = new self();
        return $instance->getExpectedCash($session);
    }

    private function getExpectedCash(\App\Models\PosSession $session): float
    {
        // Sum actual cash received (paid_amount) from ALL cash transactions
        // For eceran sessions, include grosir cash too since they share the same drawer
        $cashRevenueQuery = Transaction::query()
            ->where('status', 'completed')
            ->whereIn('payment_method', ['cash', 'tunai'])
            ->where('user_id', $session->user_id)
            ->where('created_at', '>=', $session->created_at);

        if ($session->type === 'eceran') {
            $cashRevenueQuery->whereIn('sale_type', ['eceran', 'grosir']);
        } else {
            $cashRevenueQuery->where('sale_type', $session->type);
        }

        $cashRevenue = (float) $cashRevenueQuery->sum('paid_amount');
        $creditDp = $this->getCreditDpTotal($session);
        $cashIn = $this->getCashInTotal($session->id);
        $cashOut = $this->getCashOutTotal($session->id);

        return (float) $session->opening_amount + $cashRevenue + $creditDp + $cashIn - $cashOut;
    }

    public function index()
    {
        // Eceran session (for modal awal and closing)
        $eceranSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'eceran')
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        $eceranRevenue = 0;

        if ($eceranSession) {
            // Include grosir cash too since they share the same drawer
            $eceranRevenue = Transaction::where('status', 'completed')
                ->whereIn('payment_method', ['cash', 'tunai'])
                ->where('user_id', Auth::id())
                ->whereIn('sale_type', ['eceran', 'grosir'])
                ->where('created_at', '>=', $eceranSession->created_at)
                ->sum('paid_amount');
        }

        // Grosir berdiri sendiri — revenue dihitung dari transaksi hari ini
        $grosirRevenue = Transaction::where('status', 'completed')
            ->whereIn('payment_method', ['cash', 'tunai'])
            ->where('user_id', Auth::id())
            ->where('sale_type', 'grosir')
            ->whereDate('created_at', today())
            ->sum('paid_amount');

        $grosirExpected = Transaction::where('status', 'completed')
            ->where('user_id', Auth::id())
            ->where('sale_type', 'grosir')
            ->whereDate('created_at', today())
            ->sum('paid_amount');

        $eceranExpected = $eceranSession ? $this->getExpectedCash($eceranSession) : 0;

        return view('kasir.index', compact(
            'eceranSession',
            'eceranRevenue', 'grosirRevenue',
            'eceranExpected', 'grosirExpected'
        ));
    }

    public function session()
    {
        // Grosir mengikuti sesi eceran — hanya eceran yang punya sesi sendiri
        $eceranSession = \App\Models\PosSession::with('user')
            ->where('status', 'open')
            ->where('type', 'eceran')
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        // Helper: calculate stats for a given session
        $calcStats = function ($session) {
            if (! $session) {
                return null;
            }

            // For eceran sessions, include grosir stats too (shared drawer)
            $saleTypes = $session->type === 'eceran' ? ['eceran', 'grosir'] : [$session->type];

            $cashRevenue = Transaction::where('status', 'completed')
                ->whereIn('payment_method', ['cash', 'tunai'])
                ->where('user_id', $session->user_id)
                ->whereIn('sale_type', $saleTypes)
                ->where('created_at', '>=', $session->created_at)
                ->sum('paid_amount');

            $creditDp = $this->getCreditDpTotal($session);
            $cashIn = $this->getCashInTotal($session->id);
            $cashOut = $this->getCashOutTotal($session->id);

            $nonCashRevenue = Transaction::where('status', 'completed')
                ->whereNotIn('payment_method', ['cash', 'tunai'])
                ->where('user_id', $session->user_id)
                ->whereIn('sale_type', $saleTypes)
                ->where('created_at', '>=', $session->created_at)
                ->sum('paid_amount');

            $totalRevenue = Transaction::where('status', 'completed')
                ->where('user_id', $session->user_id)
                ->whereIn('sale_type', $saleTypes)
                ->where('created_at', '>=', $session->created_at)
                ->sum('paid_amount');

            $cashTransactions = Transaction::where('status', 'completed')
                ->whereIn('payment_method', ['cash', 'tunai'])
                ->where('user_id', $session->user_id)
                ->whereIn('sale_type', $saleTypes)
                ->where('created_at', '>=', $session->created_at)
                ->count();

            $totalTransactions = Transaction::where('status', 'completed')
                ->where('user_id', $session->user_id)
                ->whereIn('sale_type', $saleTypes)
                ->where('created_at', '>=', $session->created_at)
                ->whereNull('parent_transaction_id')
                ->count();

            $expectedCash = $this->getExpectedCash($session);

            $cashMovements = PosCashMovement::query()
                ->with('user')
                ->where('pos_session_id', $session->id)
                ->latest()
                ->limit(25)
                ->get();

            return compact(
                'cashRevenue', 'creditDp', 'cashIn', 'cashOut',
                'nonCashRevenue', 'totalRevenue',
                'cashTransactions', 'totalTransactions',
                'expectedCash', 'cashMovements'
            );
        };

        $eceranStats = $calcStats($eceranSession);

        return view('kasir.session', compact(
            'eceranSession',
            'eceranStats'
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
                'sale_type' => 'eceran',
                'status' => 'completed',
            ]);

            foreach ($request->items as $item) {
                $warehouseId = $item['warehouse_id'] ?? null;
                if (! $warehouseId) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Gudang wajib dipilih untuk setiap item.',
                    ], 422);
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $warehouseId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Kurangi stok global
                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);

                // Kurangi stok per-gudang (FIFO dari product_stocks)
                $this->deductWarehouseStock($item['product_id'], $warehouseId, $item['quantity'], $transaction->id);
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
     * Kurangi stok di product_stocks secara FIFO (per warehouse).
     */
    private function deductWarehouseStock(int $productId, int $warehouseId, int $qty, int $transactionId): void
    {
        $remaining = $qty;

        $stocks = ProductStock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
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
                'batch_number' => $stock->batch_number,
                'expired_date' => $stock->expired_date,
                'quantity' => $deduct,
                'balance' => $stock->stock,
                'notes' => '[POS] Transaksi #'.$transactionId,
                'user_id' => Auth::id(),
            ]);

            $remaining -= $deduct;
        }

        if ($remaining > 0) {
            throw new \Exception('Stok gudang tidak mencukupi untuk produk ini, kurang ' . $remaining . ' pcs.');
        }
    }

    public function openSession(Request $request)
    {
        // Hanya Supervisor yang boleh membuka sesi kasir eceran (mengatur modal awal)
        if (Auth::user()->role !== 'supervisor') {
            return back()->with('error', 'Hanya Supervisor yang dapat membuka sesi kasir eceran dan menentukan modal awal.');
        }

        $request->validate([
            'opening_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Cek jika sudah ada sesi kasir eceran aktif untuk user ini
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'eceran')
            ->where('user_id', Auth::id())
            ->first();

        if ($activeSession) {
            return back()->with('error', 'Sesi kasir eceran Anda sudah aktif.');
        }

        // Modal awal selalu tunai (uang fisik di laci)
        \App\Models\PosSession::create([
            'user_id' => Auth::id(),
            'type' => 'eceran',
            'opening_amount' => $request->opening_amount,
            'payment_method' => 'Tunai',
            'notes' => $request->notes,
            'status' => 'open',
        ]);

        return redirect()->route('kasir.index')->with('success', 'Sesi Kasir Eceran berhasil dibuka dengan Modal Awal: Rp '.number_format($request->opening_amount, 0, ',', '.'));
    }

    /**
     * Supervisor membuka sesi kasir eceran untuk kasir tertentu (admin1/admin2).
     */
    public function openSessionFor(Request $request)
    {
        if (Auth::user()->role !== 'supervisor') {
            return back()->with('error', 'Hanya Supervisor yang dapat membuka sesi untuk kasir lain.');
        }

        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'opening_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $targetUser = \App\Models\User::find($request->target_user_id);

        if (! in_array($targetUser->role, ['admin1', 'admin2'], true)) {
            return back()->with('error', 'Sesi kasir hanya bisa dibuka untuk user dengan role admin1 atau admin2.');
        }

        // Cek apakah sudah ada sesi eceran aktif untuk user target
        $existing = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'eceran')
            ->where('user_id', $request->target_user_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Sesi kasir eceran untuk ' . $targetUser->name . ' sudah aktif.');
        }

        \App\Models\PosSession::create([
            'user_id' => $request->target_user_id,
            'type' => 'eceran',
            'opening_amount' => $request->opening_amount,
            'payment_method' => 'Tunai',
            'notes' => $request->notes ?? ('Modal dibuka oleh Supervisor: ' . Auth::user()->name),
            'status' => 'open',
        ]);

        return back()->with('success', 'Sesi Kasir Eceran untuk ' . $targetUser->name . ' berhasil dibuka. Modal: Rp ' . number_format($request->opening_amount, 0, ',', '.'));
    }

    public function openSessionGrosir(Request $request)
    {
        // Hanya Supervisor yang boleh membuka sesi kasir grosir
        if (Auth::user()->role !== 'supervisor') {
            return back()->with('error', 'Hanya Supervisor yang dapat membuka sesi kasir grosir.');
        }

        // Cek jika sudah ada sesi kasir grosir aktif untuk user ini
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'grosir')
            ->where('user_id', Auth::id())
            ->first();

        if ($activeSession) {
            return back()->with('error', 'Sesi kasir grosir Anda sudah aktif.');
        }

        // Grosir tidak memerlukan modal awal — langsung buka
        \App\Models\PosSession::create([
            'user_id' => Auth::id(),
            'type' => 'grosir',
            'opening_amount' => 0,
            'payment_method' => 'Tunai',
            'status' => 'open',
        ]);

        return redirect()->route('kasir.index')->with('success', 'Sesi Kasir Grosir berhasil dibuka.');
    }

    /**
     * Supervisor membuka sesi kasir grosir untuk kasir tertentu (admin1/admin2) dari Rekap Harian.
     */
    public function openSessionGrosirFor(Request $request)
    {
        if (Auth::user()->role !== 'supervisor') {
            return back()->with('error', 'Hanya Supervisor yang dapat membuka sesi grosir untuk kasir lain.');
        }

        $request->validate([
            'target_user_id' => 'required|exists:users,id',
        ]);

        $targetUser = \App\Models\User::find($request->target_user_id);

        if (! in_array($targetUser->role, ['admin1', 'admin2'], true)) {
            return back()->with('error', 'Sesi kasir hanya bisa dibuka untuk user dengan role admin1 atau admin2.');
        }

        $existing = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'grosir')
            ->where('user_id', $request->target_user_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Sesi kasir grosir untuk ' . $targetUser->name . ' sudah aktif.');
        }

        \App\Models\PosSession::create([
            'user_id' => $request->target_user_id,
            'type' => 'grosir',
            'opening_amount' => 0,
            'payment_method' => 'Tunai',
            'notes' => 'Sesi grosir dibuka oleh Supervisor: ' . Auth::user()->name,
            'status' => 'open',
        ]);

        return back()->with('success', 'Sesi Kasir Grosir untuk ' . $targetUser->name . ' berhasil dibuka.');
    }

    public function closeSession(Request $request)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'eceran')
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if (! $activeSession) {
            return back()->with('error', 'Anda belum membuka sesi kasir eceran aktif.');
        }

        $expectedCash = $this->getExpectedCash($activeSession);
        $actualCash = (float) $request->actual_cash;
        $variance = $actualCash - $expectedCash;

        // Hitung pendapatan grosir selama sesi eceran berjalan
        $grosirRevenue = Transaction::where('status', 'completed')
            ->where('user_id', Auth::id())
            ->where('sale_type', 'grosir')
            ->where('created_at', '>=', $activeSession->created_at)
            ->sum('paid_amount');

        $closingNotes = $request->notes ?: $activeSession->notes;
        if ($grosirRevenue > 0) {
            $closingNotes = trim($closingNotes . ' | Grosir periode ini: Rp ' . number_format($grosirRevenue, 0, ',', '.'));
        }

        $activeSession->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closing_amount' => $actualCash,
            'expected_cash' => $expectedCash,
            'actual_cash' => $actualCash,
            'cash_variance' => $variance,
            'notes' => $closingNotes,
        ]);

        // Tutup otomatis sesi grosir (jika ada) saat eceran ditutup
        $grosirSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'grosir')
            ->where('user_id', Auth::id())
            ->first();

        if ($grosirSession) {
            $grosirSession->update([
                'status' => 'closed',
                'closed_at' => now(),
                'closing_amount' => $grosirRevenue,
                'expected_cash' => $grosirRevenue,
                'actual_cash' => $grosirRevenue,
                'cash_variance' => 0,
                'notes' => 'Otomatis ditutup bersamaan dengan sesi eceran. Pendapatan grosir: Rp ' . number_format($grosirRevenue, 0, ',', '.'),
            ]);
        }

        return redirect()->route('kasir.session')->with('success', 'Sesi Eceran berhasil ditutup. Kas: Rp ' . number_format($actualCash, 0, ',', '.') . ($grosirRevenue > 0 ? ' | Grosir: Rp ' . number_format($grosirRevenue, 0, ',', '.') : ''));
    }

    /**
     * Deprecated: Grosir tidak lagi memiliki sesi terpisah.
     * Method ini disimpan untuk kompatibilitas route lama.
     */
    public function closeSessionGrosir(Request $request)
    {
        return redirect()->route('kasir.session')->with('info', 'Sesi grosir ditutup otomatis bersamaan dengan sesi eceran.');
    }

    public function rekapHarian()
    {
        if (Auth::user()->role !== 'supervisor') {
            return redirect()->route('kasir.session')->with('error', 'Akses ditolak. Hanya Supervisor yang dapat melihat Rekap Harian.');
        }

        // Ambil sesi ECERAN hari ini ATAU sesi eceran yang masih open (grosir tidak punya sesi terpisah)
        $sessions = \App\Models\PosSession::with('user')
            ->where('type', 'eceran')
            ->whereDate('created_at', today())
            ->latest('created_at')
            ->get();

        // Hitung detail per sesi (revenue, expected cash)
        $totalExpectedCash = 0;
        $totalActualCash = 0;
        $totalVariance = 0;

        foreach ($sessions as $session) {
            $expected = $this->getExpectedCash($session);
            $session->calculated_expected_cash = $expected;

            $sessionRevenueQuery = Transaction::where('status', 'completed')
                ->where('user_id', $session->user_id)
                ->where('created_at', '>=', $session->created_at)
                ->when($session->closed_at, fn ($q) => $q->where('created_at', '<=', $session->closed_at));
            
            if ($session->type === 'eceran') {
                $sessionRevenueQuery->whereIn('sale_type', ['eceran', 'grosir']);
            } else {
                $sessionRevenueQuery->where('sale_type', $session->type);
            }
            
            $sessionRevenue = $sessionRevenueQuery->sum('total_amount');

            $sessionTotalTrx = Transaction::where('status', 'completed')
                ->where('user_id', $session->user_id)
                ->whereNull('parent_transaction_id')
                ->where('created_at', '>=', $session->created_at)
                ->when($session->closed_at, fn ($q) => $q->where('created_at', '<=', $session->closed_at))
                ->when($session->type === 'eceran', fn ($q) => $q->whereIn('sale_type', ['eceran', 'grosir']))
                ->when($session->type !== 'eceran', fn ($q) => $q->where('sale_type', $session->type))
                ->count();

            $session->revenue = $sessionRevenue;
            $session->total_transactions = $sessionTotalTrx;

            $totalExpectedCash += $expected;

            // Actual cash & variance hanya dihitung dari sesi yang sudah ditutup
            if ($session->status === 'closed') {
                $totalActualCash += $session->actual_cash ?? 0;
                $totalVariance += ($session->actual_cash ?? 0) - $expected;
            }
        }

        // Omzet & jumlah transaksi hari ini (langsung dari tabel transaksi, bukan dari sesi)
        $todayRevenue = Transaction::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $totalTransactions = Transaction::where('status', 'completed')
            ->whereNull('parent_transaction_id')
            ->whereDate('created_at', today())
            ->count();

        // Rekap pendapatan per kasir (gabungan eceran + grosir)
        // Grosir revenue dihitung dari transaksi, bukan dari sesi
        $rekapPerKasir = [];
        foreach ($sessions as $session) {
            $kasirName = $session->user->name ?? 'Tidak Diketahui';
            if (!isset($rekapPerKasir[$kasirName])) {
                $rekapPerKasir[$kasirName] = ['revenue' => 0, 'revenue_eceran' => 0, 'revenue_grosir' => 0, 'transactions' => 0];
            }
            
            $eceranRevenue = Transaction::where('status', 'completed')
                ->where('user_id', $session->user_id)
                ->where('sale_type', 'eceran')
                ->where('created_at', '>=', $session->created_at)
                ->when($session->closed_at, fn ($q) => $q->where('created_at', '<=', $session->closed_at))
                ->sum('total_amount');
            
            $grosirRevenue = Transaction::where('status', 'completed')
                ->where('user_id', $session->user_id)
                ->where('sale_type', 'grosir')
                ->where('created_at', '>=', $session->created_at)
                ->when($session->closed_at, fn ($q) => $q->where('created_at', '<=', $session->closed_at))
                ->sum('total_amount');
            
            $rekapPerKasir[$kasirName]['revenue_eceran'] += $eceranRevenue;
            $rekapPerKasir[$kasirName]['revenue_grosir'] += $grosirRevenue;
            $rekapPerKasir[$kasirName]['revenue'] += $eceranRevenue + $grosirRevenue;
            $rekapPerKasir[$kasirName]['transactions'] += $session->total_transactions;
        }

        // Hitung sesi grosir yatim (orphaned) yang masih open (legacy dari sistem lama)
        $orphanedGrosirCount = \App\Models\PosSession::where('type', 'grosir')->where('status', 'open')->count();

        // Daftar user admin1/admin2 beserta status sesi eceran mereka
        $kasirUsers = \App\Models\User::whereIn('role', ['admin1', 'admin2'])
            ->with(['eceranSession' => function ($q) {
                $q->where('type', 'eceran')->where('status', 'open');
            }])
            ->get()
            ->each(function ($user) {
                $user->eceran_session = $user->eceranSession->first();
            });

        return view('kasir.rekap_harian', compact(
            'sessions', 'todayRevenue', 'totalExpectedCash', 'totalActualCash', 'totalVariance',
            'totalTransactions', 'rekapPerKasir', 'kasirUsers', 'orphanedGrosirCount'
        ));
    }

    /**
     * Bersihkan sesi grosir yatim (orphaned) yang masih open dari sistem lama.
     */
    public function cleanupOrphanedGrosirSessions()
    {
        if (Auth::user()->role !== 'supervisor') {
            return back()->with('error', 'Akses ditolak.');
        }

        $orphaned = \App\Models\PosSession::where('type', 'grosir')->where('status', 'open')->get();

        foreach ($orphaned as $session) {
            $expectedCash = $this->getExpectedCash($session);
            $session->update([
                'status' => 'closed',
                'closed_at' => now(),
                'closing_amount' => $expectedCash,
                'expected_cash' => $expectedCash,
                'actual_cash' => $expectedCash,
                'cash_variance' => 0,
                'notes' => 'Otomatis dibersihkan oleh Supervisor (sesi legacy)',
            ]);
        }

        return back()->with('success', count($orphaned) . ' sesi grosir lama berhasil dibersihkan.');
    }

    public function forceCloseSession(Request $request, \App\Models\PosSession $session)
    {
        if (Auth::user()->role !== 'supervisor') {
            return back()->with('error', 'Akses ditolak.');
        }

        if ($session->status !== 'open') {
            return back()->with('error', 'Sesi sudah ditutup.');
        }

        $expectedCash = $this->getExpectedCash($session);
        $actualCash = $expectedCash; // Force close assumes actual = expected (0 variance)

        $session->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closing_amount' => $actualCash,
            'expected_cash' => $expectedCash,
            'actual_cash' => $actualCash,
            'cash_variance' => 0,
            'notes' => 'Ditutup paksa oleh Supervisor',
        ]);

        // Auto-close grosir session if force-closing an eceran session
        if ($session->type === 'eceran') {
            $grosirSession = \App\Models\PosSession::where('status', 'open')
                ->where('type', 'grosir')
                ->where('user_id', $session->user_id)
                ->first();

            if ($grosirSession) {
                $grosirExpected = $this->getExpectedCash($grosirSession);
                $grosirSession->update([
                    'status' => 'closed',
                    'closed_at' => now(),
                    'closing_amount' => $grosirExpected,
                    'expected_cash' => $grosirExpected,
                    'actual_cash' => $grosirExpected,
                    'cash_variance' => 0,
                    'notes' => 'Otomatis ditutup bersamaan dengan force-close sesi eceran.',
                ]);
            }
        }

        return back()->with('success', 'Sesi kasir ' . ($session->user->name ?? '') . ' berhasil ditutup secara paksa.');
    }

    public function addCashMovement(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'session_type' => 'required|in:eceran',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:500',
        ]);

        $sessionType = $request->session_type;
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', $sessionType)
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if (! $activeSession) {
            return back()->with('error', 'Anda belum membuka sesi kasir eceran aktif.');
        }

        PosCashMovement::create([
            'pos_session_id' => $activeSession->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Cash '.($request->type === 'in' ? 'In' : 'Out').' berhasil dicatat untuk laci Anda.');
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
            ->get()
            ->map(fn($p) => $transaction->sale_type === 'grosir'
                ? $productService->formatProductGrosir($p)
                : $productService->formatProductEceran($p))
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

        // Validate POS session
        try {
            $this->transactionService->validatePosSession($transaction->sale_type ?? 'eceran');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.warehouse_id' => 'required|exists:warehouses,id',
            'items.*.unit_name' => 'nullable|string',
            'items.*.unit_id' => 'nullable|integer',
            'additional_payment' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,kredit',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $allowedRoles = ['admin1', 'admin2', 'supervisor'];
        $isOverrideAllowed = in_array(Auth::user()->role, $allowedRoles);

        try {
            DB::beginTransaction();

            // Lock and load products
            $productIds = collect($request->items)->pluck('product_id')->unique()->values()->toArray();
            $products = $this->transactionService->lockAndLoadProducts($productIds);

            $totalAmount = 0;
            $resolvedItems = [];
            $runningStock = [];

            foreach ($request->items as $item) {
                $product = $products->get($item['product_id']);
                if (! $product) {
                    throw new \Exception("Produk dengan ID {$item['product_id']} tidak ditemukan.");
                }

                // Resolve unit conversion
                $unitName = $item['unit_name'] ?? null;
                $unitId = $item['unit_id'] ?? null;
                $unitConversion = null;
                $conversionFactor = 1;

                if ($unitId) {
                    $unitConversion = \App\Models\ProductUnitConversion::where('product_id', $item['product_id'])
                        ->where('unit_id', $unitId)
                        ->first();
                    if ($unitConversion) {
                        $conversionFactor = $unitConversion->conversion_factor ?: 1;
                        $unitName = $unitConversion->unit?->name ?? $unitName;
                    }
                }

                $unitQty = (int) $item['quantity'];
                $baseFactor = max(0.0001, (float) $conversionFactor);
                $baseQty = (int) round($unitQty * $baseFactor);

                if ($baseQty < 1) {
                    throw new \Exception('Jumlah produk "' . ($product->name ?? 'ID:' . $item['product_id']) . '" terlalu kecil untuk dikonversi ke satuan dasar (hasil: 0).');
                }

                // Check stock availability
                $key = $product->id . '_' . $item['warehouse_id'];
                $runningStock[$key] = ($runningStock[$key] ?? 0) + $baseQty;

                $availableStock = $product->productStocks
                    ->where('warehouse_id', $item['warehouse_id'])
                    ->sum('stock');

                if ($availableStock < $runningStock[$key]) {
                    $whRecord = $product->productStocks->firstWhere('warehouse_id', $item['warehouse_id']);
                    $whName = $whRecord?->warehouse?->name ?? 'Gudang Dipilih';
                    throw new \Exception('Stok produk "' . ($product->name ?? 'ID:' . $item['product_id']) . '" di ' . $whName . ' tidak mencukupi (' . $runningStock[$key] . ' base unit). Tersedia: ' . $availableStock . ' base item.');
                }

                // Validate price override minimum
                $unitPrice = (float) $item['price'];
                if ($isOverrideAllowed && $unitConversion) {
                    $purchasePrice = $this->transactionService->getPriceService()->parseNumber($unitConversion->purchase_price ?? 0);
                    $minimalPrice = $purchasePrice * 1.05;
                    $sellMinimal = $this->transactionService->getPriceService()->parseNumber($unitConversion->sell_price_minimal ?? 0);
                    if ($sellMinimal > 0) {
                        $minimalPrice = max($minimalPrice, $sellMinimal);
                    }
                    if ($purchasePrice > 0 && $unitPrice < $minimalPrice) {
                        throw new \Exception('Harga untuk "' . $product->name . '" tidak boleh di bawah Rp ' . number_format($minimalPrice, 0, ',', '.') . ' (minimal di atas harga modal/harga minimal).');
                    }
                }

                $subtotal = round($unitPrice * $unitQty, 2);
                $totalAmount += $subtotal;

                $resolvedItems[] = [
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity' => $baseQty,
                    'unit_qty' => $unitQty,
                    'unit_name' => $unitName,
                    'unit_conversion_id' => $unitConversion?->id,
                    'price' => $unitPrice,
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
                'source_warehouse_id' => $resolvedItems[0]['warehouse_id'] ?? null,
                'total_amount' => $totalAmount,
                'paid_amount' => $additionalPayment,
                'change_amount' => max(0, $additionalPayment - $totalAmount),
                'payment_method' => $paymentMethod,
                'payment_reference' => $paymentReference,
                'sale_type' => $transaction->sale_type ?? 'eceran',
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
                    'unit_qty' => $item['unit_qty'],
                    'unit_name' => $item['unit_name'],
                    'unit_conversion_id' => $item['unit_conversion_id'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Deduct global stock (use already-locked product)
                $lockedProduct = $products->get($item['product_id']);
                if ($lockedProduct) {
                    $lockedProduct->stock -= $item['quantity'];
                    $lockedProduct->save();
                }

                // Deduct per-warehouse stock (FIFO) — also records StockMovement
                $this->deductWarehouseStock($item['product_id'], $item['warehouse_id'], $item['quantity'], $additionalTransaction->id);
            }

            // Add items to the ORIGINAL pick order (not a new one)
            $originalPickOrder = \App\Models\PosPickOrder::where('transaction_id', $transaction->id)
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->first();

            $pickOrderNumber = null;
            if ($originalPickOrder) {
                // Add new items to existing pick order with is_additional flag
                foreach ($additionalTransaction->details as $detail) {
                    $product = \App\Models\Product::find($detail->product_id);
                    $unitQty = $detail->unit_qty ?? $detail->quantity;
                    $unitName = $detail->unit_name ?? 'pcs';

                    \App\Models\PosPickOrderItem::create([
                        'pick_order_id'         => $originalPickOrder->id,
                        'transaction_detail_id' => $detail->id,
                        'product_id'            => $detail->product_id,
                        'quantity'              => $detail->quantity,
                        'unit_qty'              => $unitQty,
                        'unit_name'             => $unitName,
                        'is_additional'         => true,
                    ]);
                }

                // Reset to pending if already completed, so admin3 re-processes
                if (in_array($originalPickOrder->status, ['completed', 'ready'])) {
                    $originalPickOrder->update([
                        'status' => 'pending',
                        'notes'  => ($originalPickOrder->notes ? $originalPickOrder->notes . ' | ' : '') .
                            '[TAMBAHAN] Item baru ditambahkan untuk ' . $transaction->invoice_number,
                    ]);
                } else {
                    $originalPickOrder->update([
                        'notes' => ($originalPickOrder->notes ? $originalPickOrder->notes . ' | ' : '') .
                            '[TAMBAHAN] Item baru ditambahkan untuk ' . $transaction->invoice_number,
                    ]);
                }
                $pickOrderNumber = $originalPickOrder->pick_number;
            } else {
                // Fallback: create new pick order if no original found
                $pickOrderService = app(\App\Services\PosPickOrderService::class);
                $newPickOrder = $pickOrderService->createFromTransaction($additionalTransaction, $transaction->sale_type ?? 'eceran', $transaction->invoice_number);
                $pickOrderNumber = $newPickOrder ? $newPickOrder->pick_number : null;
            }
            if ($paymentMethod === 'kredit') {
                $debtAmount = $totalAmount - $additionalPayment;
                if ($debtAmount > 0 && $transaction->customer_id) {
                    $customer = \App\Models\Customer::find($transaction->customer_id);
                    if ($customer) {
                        $transactionService = app(\App\Services\PosTransactionService::class);
                        $transactionService->createCustomerCredit(
                            $customer->id,
                            $additionalTransaction->id,
                            $debtAmount,
                            'Tambahan Item POS - #' . ($transaction->invoice_number ?? $transaction->id)
                        );
                        $customer->refreshDebt();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan!',
                'additional_transaction_id' => $additionalTransaction->id,
                'grand_total' => $transaction->grand_total,
                'pick_order' => $pickOrderNumber,
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
