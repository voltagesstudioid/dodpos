<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\SdmDeduction;
use App\Models\SdmBonus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PotonganGajiController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $m] = explode('-', $month);

        $queryDeduction = SdmDeduction::with('user')
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->orderBy('date', 'desc');

        $queryBonus = SdmBonus::with('user')
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->orderBy('date', 'desc');

        if ($request->filled('user_id')) {
            $queryDeduction->where('user_id', $request->user_id);
            $queryBonus->where('user_id', $request->user_id);
        }

        $currentUserId = Auth::id();
        $isSupervisor = strtolower((string) (Auth::user()?->role ?? '')) === 'supervisor';
        if (! $isSupervisor && $currentUserId) {
            $queryDeduction->where('user_id', $currentUserId);
            $queryBonus->where('user_id', $currentUserId);
        }

        $deductions = $queryDeduction->get();
        $bonuses = $queryBonus->get();
        
        $usersQuery = User::whereHas('employee');
        if (! $isSupervisor && $currentUserId) {
            $usersQuery->where('id', $currentUserId);
        }
        $users = $usersQuery->orderBy('name')->get();

        return view('sdm.potongan.index', compact('deductions', 'bonuses', 'month', 'users'));
    }

    public function selfIndex(Request $request)
    {
        $user = $request->user();
        if (! $user?->employee || ! $user->employee->active) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terdaftar sebagai karyawan aktif.');
        }

        $month = $request->input('month', now()->format('Y-m'));
        [$year, $m] = explode('-', $month);

        $deductions = SdmDeduction::query()
            ->where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->orderBy('date', 'desc')
            ->get();

        $bonuses = SdmBonus::query()
            ->where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->orderBy('date', 'desc')
            ->get();

        return view('sdm.potongan.self', compact('deductions', 'bonuses', 'month'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'type' => 'required|in:potongan,bonus',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validated['type'] === 'bonus') {
            SdmBonus::create([
                'user_id' => $validated['user_id'],
                'date' => $validated['date'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
            ]);
            $msg = 'Bonus berhasil ditambahkan.';
        } else {
            SdmDeduction::create([
                'user_id' => $validated['user_id'],
                'date' => $validated['date'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
            ]);
            $msg = 'Potongan gaji berhasil ditambahkan.';
        }

        return redirect()->route('sdm.potongan.index')->with('success', $msg);
    }

    public function destroy(SdmDeduction $potongan)
    {
        $potongan->delete();

        return redirect()->route('sdm.potongan.index')->with('success', 'Data potongan berhasil dihapus.');
    }

    public function destroyBonus(SdmBonus $potongan)
    {
        $potongan->delete();

        return redirect()->route('sdm.potongan.index')->with('success', 'Data bonus berhasil dihapus.');
    }
}
