<?php

namespace App\Http\Controllers;

use App\Models\OperationalExpense;
use App\Models\OperationalCategory;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $totalOpening = (float) \App\Models\OperationalSession::sum('opening_amount');
        $totalUsed = (float) OperationalExpense::whereNotNull('operational_session_id')->sum('amount');
        $totalRemaining = max(0, $totalOpening - $totalUsed);

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
        $activeSession = \App\Models\OperationalSession::where('status', 'open')->latest()->first();
        if (!$activeSession) {
            return view('operasional.closed');
        }

        $query = OperationalExpense::with(['category', 'vehicle', 'user'])->latest('date');

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Get totals before pagination
        $totalAmount = (clone $query)->sum('amount');
        $totalRecords = (clone $query)->count();

        $expenses = $query->paginate(25);
        $categories = OperationalCategory::all();
        return view('operasional.riwayat.index', compact('expenses', 'categories', 'startDate', 'endDate', 'totalAmount', 'totalRecords'));
    }

    /**
     * Form Input Pengeluaran Baru
     */
    public function create()
    {
        $activeSession = \App\Models\OperationalSession::where('status', 'open')->latest()->first();
        if (!$activeSession) {
            return view('operasional.closed');
        }

        $categories = OperationalCategory::all();
        $vehicles = Vehicle::all();
        return view('operasional.pengeluaran.create', compact('categories', 'vehicles'));
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

        $activeSession->update([
            'status' => 'closed',
            'closing_amount' => $request->closing_amount ?? 0,
            'closed_at' => now(), // Assume keeping track of when it closed might be useful, but standard timestamps handles updated_at. We won't strictly enforce an extra col unless needed.
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

        OperationalExpense::create([
            'date' => $request->date,
            'category_id' => $request->category_id,
            'vehicle_id' => $request->vehicle_id,
            'operational_session_id' => $activeSession ? $activeSession->id : null,
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
}
