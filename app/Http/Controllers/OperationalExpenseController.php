<?php

namespace App\Http\Controllers;

use App\Models\OperationalExpense;
use App\Models\OperationalCategory;
use App\Models\OperationalSession;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class OperationalExpenseController extends Controller
{
    /**
     * Tampilan Riwayat Sesi Operasional
     */
    public function sessions(Request $request)
    {
        $activeSession = \App\Models\OperationalSession::with(['user'])
            ->withSum('expenses', 'amount')
            ->where('status', 'open')
            ->latest()
            ->first();

        $totalSessions = \App\Models\OperationalSession::count();
        $openSessionsCount = \App\Models\OperationalSession::where('status', 'open')->count();
        if ($activeSession) {
            $totalOpening = (float) $activeSession->opening_amount;
            $totalUsed = (float) $activeSession->expenses()->sum('amount');
            $totalRemaining = $totalOpening - $totalUsed;
        } else {
            $totalOpening = 0;
            $totalUsed = 0;
            $totalRemaining = 0;
        }

        $sessions = \App\Models\OperationalSession::with(['user'])
            ->withSum('expenses', 'amount')
            ->latest()
            ->paginate(20);

        return view('operasional.sesi.index', compact(
            'sessions',
            'activeSession',
            'totalSessions',
            'openSessionsCount',
            'totalOpening',
            'totalUsed',
            'totalRemaining'
        ));
    }

    /**
     * Tampilan Riwayat Pengeluaran Operasional
     */
    public function index(Request $request)
    {
        $activeSession = \App\Models\OperationalSession::with(['user'])
            ->withSum('expenses', 'amount')
            ->where('status', 'open')
            ->latest()
            ->first();

        $query = OperationalExpense::with(['category', 'vehicle', 'user'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('date', '<=', $endDate);
        } else {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $selectedCategoryId = $request->input('category_id');
        if ($selectedCategoryId) {
            $query->where('category_id', $selectedCategoryId);
        }

        $totalAmount = (clone $query)->sum('amount');
        $totalRecords = (clone $query)->count();

        $expenses = $query->paginate(25);
        $categories = OperationalCategory::all();
        return view('operasional.riwayat.index', compact(
            'expenses', 'categories', 'startDate', 'endDate',
            'totalAmount', 'totalRecords', 'activeSession', 'selectedCategoryId'
        ));
    }

    /**
     * Form Input Pengeluaran Baru
     */
    public function create()
    {
        $activeSession = \App\Models\OperationalSession::with(['user'])
            ->withSum('expenses', 'amount')
            ->where('status', 'open')
            ->latest()
            ->first();
        if (!$activeSession) {
            return view('operasional.closed');
        }

        $categories = OperationalCategory::all();
        $vehicles = Vehicle::all();
        return view('operasional.pengeluaran.create', compact('categories', 'vehicles', 'activeSession'));
    }

    /**
     * Buka Sesi Operasional
     */
    public function openSession(Request $request)
    {
        $request->validate([
            'opening_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $activeSession = \App\Models\OperationalSession::where('status', 'open')->first();
        if ($activeSession) {
            return back()->with('error', 'Sesi operasional sudah terbuka sebelumnya.');
        }

        \App\Models\OperationalSession::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'opening_amount' => $request->opening_amount,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'status' => 'open'
        ]);

        return redirect()->route('operasional.riwayat.index')->with('success', 'Sesi Operasional berhasil dibuka dengan Modal Awal: Rp ' . number_format($request->opening_amount, 0, ',', '.'));
    }

    /**
     * Tutup Sesi Operasional
     */
    public function closeSession(Request $request)
    {
        $activeSession = \App\Models\OperationalSession::where('status', 'open')->first();
        if (!$activeSession) {
            return back()->with('error', 'Sesi operasional belum dimulai atau sudah ditutup.');
        }

        $totalUsed = $activeSession->expenses()->sum('amount');
        $expectedClosing = $activeSession->opening_amount - $totalUsed;
        $closingAmount = $request->filled('closing_amount') ? $request->closing_amount : $expectedClosing;

        $activeSession->update([
            'status' => 'closed',
            'closing_amount' => $closingAmount,
        ]);

        return redirect()->route('dashboard')->with('success', 'Sesi Operasional berhasil ditutup.');
    }

    /**
     * Proses Simpan Pengeluaran Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:operational_categories,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $activeSession = \App\Models\OperationalSession::where('status', 'open')->latest()->first();
        if (!$activeSession) {
            return redirect()->route('operasional.pengeluaran.create')
                ->with('error', 'Sesi operasional tidak aktif atau sudah ditutup. Silakan buka sesi terlebih dahulu.')
                ->withInput();
        }

        OperationalExpense::create([
            'date' => $request->date,
            'category_id' => $request->category_id,
            'vehicle_id' => $request->vehicle_id,
            'operational_session_id' => $activeSession->id,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('operasional.pengeluaran.create')->with('success', 'Pengeluaran operasional berhasil dicatat.');
    }
    /**
     * Form Edit Pengeluaran
     */
    public function edit(OperationalExpense $pengeluaran)
    {
        $categories = OperationalCategory::all();
        $vehicles = Vehicle::all();
        return view('operasional.pengeluaran.edit', compact('pengeluaran', 'categories', 'vehicles'));
    }

    /**
     * Proses Update Pengeluaran
     */
    public function update(Request $request, OperationalExpense $pengeluaran)
    {
        $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|exists:operational_categories,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $pengeluaran->update([
            'date' => $request->date,
            'category_id' => $request->category_id,
            'vehicle_id' => $request->vehicle_id,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ]);

        return redirect()->route('operasional.riwayat.index')->with('success', 'Pengeluaran operasional berhasil diperbarui.');
    }

    /**
     * Proses Hapus Pengeluaran
     */
    public function destroy(OperationalExpense $pengeluaran)
    {
        $pengeluaran->delete();
        return redirect()->route('operasional.riwayat.index')->with('success', 'Pengeluaran operasional berhasil dihapus.');
    }

    /**
     * Dashboard Operasional
     */
    public function dashboard(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = match($period) {
            'quarter' => now()->subMonths(3)->startOfMonth(),
            'year' => now()->subMonths(12)->startOfMonth(),
            default => now()->subMonths(1)->startOfMonth(),
        };

        // Statistik Utama
        $totalSessions = OperationalSession::count();
        $openSessions = OperationalSession::where('status', 'open')->count();
        $totalExpenses = OperationalExpense::count();
        $totalAmount = OperationalExpense::sum('amount');

        // Statistik Periode
        $periodExpenses = OperationalExpense::where('date', '>=', $startDate)->sum('amount');
        $periodCount = OperationalExpense::where('date', '>=', $startDate)->count();

        // Data untuk Chart (6 bulan terakhir)
        $months = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            $chartData[] = OperationalExpense::whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('amount');
        }

        // Top Kategori
        $topCategories = OperationalCategory::withSum(['expenses as total_amount' => function($q) use ($startDate) {
                $q->where('date', '>=', $startDate);
            }], 'amount')
            ->having('total_amount', '>', 0)
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        // Recent Expenses
        $recentExpenses = OperationalExpense::with(['category', 'user'])
            ->orderByDesc('date')
            ->limit(10)
            ->get();

        // Sesi Aktif
        $activeSession = OperationalSession::with(['user'])
            ->withSum('expenses', 'amount')
            ->where('status', 'open')
            ->latest()
            ->first();

        // Stats per Status Sesi
        $sessionStats = [
            'open' => OperationalSession::where('status', 'open')->count(),
            'closed' => OperationalSession::where('status', 'closed')->count(),
        ];

        // Total Modal vs Terpakai (Aktif)
        $totalModal = $activeSession ? $activeSession->opening_amount : 0;
        $totalTerpakai = $activeSession ? $activeSession->expenses()->sum('amount') : 0;

        return view('operasional.dashboard', compact(
            'totalSessions', 'openSessions', 'totalExpenses', 'totalAmount',
            'periodExpenses', 'periodCount', 'months', 'chartData',
            'topCategories', 'recentExpenses', 'activeSession',
            'sessionStats', 'totalModal', 'totalTerpakai', 'period'
        ));
    }

    /**
     * Export Riwayat Operasional ke PDF
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $categoryId = $request->input('category_id');

        $query = OperationalExpense::with(['category', 'vehicle', 'user'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $expenses = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
        $totalAmount = $expenses->sum('amount');
        $totalRecords = $expenses->count();

        $categoryName = null;
        if ($categoryId) {
            $cat = OperationalCategory::find($categoryId);
            $categoryName = $cat?->name;
        }

        $storeSetting = \App\Models\StoreSetting::current();
        $storeName = $storeSetting->store_name ?? 'DODPOS';

        $data = compact(
            'expenses', 'startDate', 'endDate',
            'totalAmount', 'totalRecords', 'categoryName', 'storeName'
        );

        $pdf = Pdf::loadView('operasional.riwayat.pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'riwayat-operasional-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
