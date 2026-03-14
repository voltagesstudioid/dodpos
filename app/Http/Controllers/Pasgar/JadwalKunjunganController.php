<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarVisitSchedule;
use App\Models\PasgarVisitReport;
use App\Models\PasgarMember;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalKunjunganController extends Controller
{
    // =========================================================
    // JADWAL KUNJUNGAN
    // =========================================================

    public function index(Request $request)
    {
        $query = PasgarVisitSchedule::with(['member.user', 'customer', 'report'])
            ->latest('scheduled_date');

        $dateFrom = $request->date_from ?? now()->startOfWeek()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->endOfWeek()->format('Y-m-d');
        $query->whereDate('scheduled_date', '>=', $dateFrom)
              ->whereDate('scheduled_date', '<=', $dateTo);

        if ($request->member_id) {
            $query->where('pasgar_member_id', $request->member_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $schedules = $query->paginate(20)->withQueryString();

        // Summary
        $summaryBase = PasgarVisitSchedule::whereDate('scheduled_date', '>=', $dateFrom)
            ->whereDate('scheduled_date', '<=', $dateTo);

        $totalScheduled = (clone $summaryBase)->count();
        $totalVisited   = (clone $summaryBase)->where('status', 'visited')->count();
        $totalSkipped   = (clone $summaryBase)->where('status', 'skipped')->count();
        $totalPending   = (clone $summaryBase)->where('status', 'scheduled')->count();

        $members = PasgarMember::with('user')->where('active', true)->get();

        return view('pasgar.jadwal.index', compact(
            'schedules', 'members', 'dateFrom', 'dateTo',
            'totalScheduled', 'totalVisited', 'totalSkipped', 'totalPending'
        ));
    }

    public function create()
    {
        $members   = PasgarMember::with('user')->where('active', true)->get();
        $customers = Customer::orderBy('name')->get(['id', 'name', 'phone', 'address']);
        return view('pasgar.jadwal.create', compact('members', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pasgar_member_id' => 'required|exists:pasgar_members,id',
            'customer_id'      => 'required|exists:customers,id',
            'scheduled_date'   => 'required|date',
            'notes'            => 'nullable|string',
        ]);

        PasgarVisitSchedule::create([
            'pasgar_member_id' => $request->pasgar_member_id,
            'customer_id'      => $request->customer_id,
            'scheduled_date'   => $request->scheduled_date,
            'status'           => 'scheduled',
            'notes'            => $request->notes,
        ]);

        return redirect()->route('pasgar.jadwal.index')
            ->with('success', 'Jadwal kunjungan berhasil ditambahkan.');
    }

    public function destroy(PasgarVisitSchedule $jadwal)
    {
        if ($jadwal->status !== 'scheduled') {
            return back()->with('error', 'Hanya jadwal berstatus "Terjadwal" yang dapat dihapus.');
        }
        $jadwal->delete();
        return redirect()->route('pasgar.jadwal.index')
            ->with('success', 'Jadwal kunjungan berhasil dihapus.');
    }

    // =========================================================
    // LAPORAN KUNJUNGAN
    // =========================================================

    public function laporanIndex(Request $request)
    {
        $query = PasgarVisitReport::with(['member.user', 'customer', 'schedule'])
            ->latest('visit_date');

        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->format('Y-m-d');
        $query->whereDate('visit_date', '>=', $dateFrom)
              ->whereDate('visit_date', '<=', $dateTo);

        if ($request->member_id) {
            $query->where('pasgar_member_id', $request->member_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(20)->withQueryString();

        // Summary
        $summaryBase = PasgarVisitReport::whereDate('visit_date', '>=', $dateFrom)
            ->whereDate('visit_date', '<=', $dateTo);

        $totalVisits      = (clone $summaryBase)->count();
        $totalWithOrder   = (clone $summaryBase)->where('status', 'order')->count();
        $totalNoOrder     = (clone $summaryBase)->where('status', 'no_order')->count();
        $totalClosed      = (clone $summaryBase)->whereIn('status', ['closed', 'not_found'])->count();
        $totalOrderAmount = (clone $summaryBase)->sum('order_amount');
        $totalCollection  = (clone $summaryBase)->sum('collection_amount');

        $members = PasgarMember::with('user')->where('active', true)->get();

        return view('pasgar.kunjungan.index', compact(
            'reports', 'members', 'dateFrom', 'dateTo',
            'totalVisits', 'totalWithOrder', 'totalNoOrder', 'totalClosed',
            'totalOrderAmount', 'totalCollection'
        ));
    }

    public function laporanCreate(Request $request)
    {
        $members   = PasgarMember::with('user')->where('active', true)->get();
        $customers = Customer::orderBy('name')->get(['id', 'name', 'phone']);

        // Load specific schedule if schedule_id is provided (from jadwal index "Laporan" button)
        $schedule = null;
        if ($request->schedule_id) {
            $schedule = PasgarVisitSchedule::with(['customer', 'member.user'])
                ->find($request->schedule_id);
        }

        return view('pasgar.kunjungan.create', compact('members', 'customers', 'schedule'));
    }

    public function laporanStore(Request $request)
    {
        $request->validate([
            'pasgar_member_id'  => 'required|exists:pasgar_members,id',
            'customer_id'       => 'required|exists:customers,id',
            'visit_date'        => 'required|date',
            'status'            => 'required|in:order,no_order,closed,not_found',
            'order_amount'      => 'nullable|numeric|min:0',
            'collection_amount' => 'nullable|numeric|min:0',
            'schedule_id'       => 'nullable|exists:pasgar_visit_schedules,id',
            'notes'             => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $report = PasgarVisitReport::create([
                'schedule_id'       => $request->schedule_id,
                'pasgar_member_id'  => $request->pasgar_member_id,
                'customer_id'       => $request->customer_id,
                'visit_date'        => $request->visit_date,
                'status'            => $request->status,
                'order_amount'      => $request->order_amount ?? 0,
                'collection_amount' => $request->collection_amount ?? 0,
                'notes'             => $request->notes,
            ]);

            // Update jadwal jika ada
            if ($request->schedule_id) {
                PasgarVisitSchedule::where('id', $request->schedule_id)
                    ->update(['status' => $request->status === 'order' || $request->status === 'no_order'
                        ? 'visited' : 'skipped']);
            }

            DB::commit();

            return redirect()->route('pasgar.kunjungan.index')
                ->with('success', 'Laporan kunjungan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage())->withInput();
        }
    }
}
