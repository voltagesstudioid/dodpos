<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakKunjungan;
use App\Models\MinyakSales;
use App\Models\MinyakPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    /**
     * Calculate distance between two GPS points in meters (Haversine formula).
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    private function isSales(): bool
    {
        $role = strtolower(Auth::user()->role ?? '');
        return str_starts_with($role, 'sales_') || $role === 'sales';
    }

    private function getSalesProfile()
    {
        return MinyakSales::where('user_id', Auth::id())->first();
    }

    public function index(Request $request)
    {
        $isSalesRole = $this->isSales();
        $tanggalMulai = $request->input('tanggal_mulai', now()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());
        $salesId = $request->input('sales_id');
        $status = $request->input('status');

        // Base query
        $query = MinyakKunjungan::query()
            ->with(['sales', 'pelanggan'])
            ->whereDate('waktu_checkin', '>=', $tanggalMulai)
            ->whereDate('waktu_checkin', '<=', $tanggalSelesai);

        // Sales users: always scope to own data
        if ($isSalesRole) {
            $profile = $this->getSalesProfile();
            if ($profile) {
                $query->where('sales_id', $profile->id);
            }
            $salesList = collect([$profile]);
        } else {
            $salesList = MinyakSales::where('status', 'aktif')->orderBy('nama')->get();
            if ($salesId) {
                $query->where('sales_id', $salesId);
            }
        }

        // Status filter
        if ($status) {
            $query->where('status', $status);
        }

        $kunjungans = $query->orderBy('waktu_checkin', 'desc')->paginate(20)->withQueryString();

        // Statistics (scoped to same filters)
        $baseQuery = MinyakKunjungan::query()
            ->whereDate('waktu_checkin', '>=', $tanggalMulai)
            ->whereDate('waktu_checkin', '<=', $tanggalSelesai);

        if ($isSalesRole && isset($profile) && $profile) {
            $baseQuery->where('sales_id', $profile->id);
        } elseif ($salesId) {
            $baseQuery->where('sales_id', $salesId);
        }

        $totalKunjungan = (clone $baseQuery)->count();
        $kunjunganSelesai = (clone $baseQuery)->whereNotNull('waktu_checkout')->count();
        $kunjunganBertransaksi = (clone $baseQuery)->where('ada_penjualan', true)->count();

        // Average duration in minutes from completed visits
        $avgDurasi = 0;
        $completedVisits = (clone $baseQuery)->whereNotNull('waktu_checkout')->get();
        if ($completedVisits->count() > 0) {
            $totalMinutes = $completedVisits->sum(function ($k) {
                return $k->waktu_checkin->diffInMinutes($k->waktu_checkout);
            });
            $avgDurasi = round($totalMinutes / $completedVisits->count());
        }

        $stats = [
            'total_kunjungan' => $totalKunjungan,
            'kunjungan_selesai' => $kunjunganSelesai,
            'kunjungan_bertransaksi' => $kunjunganBertransaksi,
            'durasi_rata_rata' => $avgDurasi,
        ];

        // Kunjungan by sales for chart
        $chartQuery = MinyakKunjungan::whereDate('waktu_checkin', '>=', $tanggalMulai)
            ->whereDate('waktu_checkin', '<=', $tanggalSelesai);
        if ($isSalesRole && isset($profile) && $profile) {
            $chartQuery->where('sales_id', $profile->id);
        }
        $kunjunganBySales = $chartQuery
            ->selectRaw('sales_id, COUNT(*) as total')
            ->groupBy('sales_id')
            ->with('sales')
            ->get();

        return view('minyak.kunjungan.index', compact(
            'kunjungans',
            'stats',
            'salesList',
            'kunjunganBySales',
            'isSalesRole',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }

    public function show(MinyakKunjungan $kunjungan)
    {
        $kunjungan->load(['sales', 'pelanggan', 'penjualans']);

        // Sales users can only see own visits
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (! $profile || $kunjungan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
            }
        }

        // Get the first penjualan linked to this kunjungan (for display in view)
        $penjualan = $kunjungan->penjualans->first();
        $kunjungan->penjualan = $penjualan;

        // Calculate GPS distance if both coordinates available
        $kunjungan->jarak_checkin = null;
        $kunjungan->jarak_checkout = null;
        if ($kunjungan->latitude_checkin && $kunjungan->longitude_checkin && $kunjungan->pelanggan->latitude && $kunjungan->pelanggan->longitude) {
            $kunjungan->jarak_checkin = round($this->calculateDistance(
                (float) $kunjungan->latitude_checkin,
                (float) $kunjungan->longitude_checkin,
                (float) $kunjungan->pelanggan->latitude,
                (float) $kunjungan->pelanggan->longitude
            ));
        }
        if ($kunjungan->latitude_checkout && $kunjungan->longitude_checkout && $kunjungan->pelanggan->latitude && $kunjungan->pelanggan->longitude) {
            $kunjungan->jarak_checkout = round($this->calculateDistance(
                (float) $kunjungan->latitude_checkout,
                (float) $kunjungan->longitude_checkout,
                (float) $kunjungan->pelanggan->latitude,
                (float) $kunjungan->pelanggan->longitude
            ));
        }

        $isSalesRole = $this->isSales();

        return view('minyak.kunjungan.show', compact('kunjungan', 'isSalesRole'));
    }

    public function checkinForm()
    {
        $pelanggans = MinyakPelanggan::where('status', 'aktif')
            ->orderBy('nama_toko')
            ->get();

        // Check if there's an active visit (checkin but no checkout)
        $profile = $this->getSalesProfile();
        $activeVisit = null;
        if ($profile) {
            $activeVisit = MinyakKunjungan::with('pelanggan')
                ->where('sales_id', $profile->id)
                ->where('status', 'checkin')
                ->latest()
                ->first();
        }

        return view('minyak.kunjungan.checkin', compact('pelanggans', 'activeVisit'));
    }

    public function storeCheckin(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:minyak_pelanggan,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'foto_base64' => 'nullable|string',
            'catatan' => 'nullable|string|max:500',
        ]);

        $profile = $this->getSalesProfile();
        if (! $profile) {
            return back()->with('error', 'Profil sales tidak ditemukan.');
        }

        // Check for duplicate active visit at same pelanggan today
        $existing = MinyakKunjungan::where('sales_id', $profile->id)
            ->where('pelanggan_id', $validated['pelanggan_id'])
            ->whereDate('waktu_checkin', now()->toDateString())
            ->where('status', 'checkin')
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki kunjungan aktif ke pelanggan ini hari ini.');
        }

        // Store photo (file upload from mobile or base64 from desktop webcam)
        $fotoPath = $this->storePhoto($request);

        // GPS radius validation (warn if > 500m from customer location)
        $gpsWarning = null;
        $pelanggan = MinyakPelanggan::find($validated['pelanggan_id']);
        if ($pelanggan && $pelanggan->latitude && $pelanggan->longitude
            && isset($validated['latitude']) && isset($validated['longitude'])) {
            $distance = $this->calculateDistance(
                (float) $validated['latitude'],
                (float) $validated['longitude'],
                (float) $pelanggan->latitude,
                (float) $pelanggan->longitude
            );
            if ($distance > 500) {
                $gpsWarning = 'Perhatian: Lokasi check-in berjarak ' . round($distance) . ' meter dari alamat pelanggan (> 500m).';
            }
        }

        MinyakKunjungan::create([
            'sales_id' => $profile->id,
            'pelanggan_id' => $validated['pelanggan_id'],
            'waktu_checkin' => now(),
            'latitude_checkin' => $validated['latitude'] ?? null,
            'longitude_checkin' => $validated['longitude'] ?? null,
            'foto_checkin' => $fotoPath,
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'checkin',
        ]);

        $message = 'Check-in berhasil dicatat.';
        if ($gpsWarning) {
            $message .= ' ' . $gpsWarning;
        }

        return redirect()->route('minyak.kunjungan.index')
            ->with('success', $message);
    }

    /**
     * Store photo from file upload (mobile) or base64 (desktop webcam).
     */
    private function storePhoto(Request $request): ?string
    {
        if ($request->hasFile('foto')) {
            return $request->file('foto')->store('kunjungan-photos/' . now()->format('Y-m-d'), 'public');
        }

        if ($request->filled('foto_base64')) {
            $base64 = $request->input('foto_base64');
            // Extract the base64 data after the comma
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                $base64Data = substr($base64, strpos($base64, ',') + 1);
                $imageData = base64_decode($base64Data);
                if ($imageData !== false) {
                    $dir = 'kunjungan-photos/' . now()->format('Y-m-d');
                    $filename = 'webcam_' . uniqid() . '.jpg';
                    \Illuminate\Support\Facades\Storage::disk('public')->put($dir . '/' . $filename, $imageData);
                    return $dir . '/' . $filename;
                }
            }
        }

        return null;
    }

    public function storeCheckout(Request $request, MinyakKunjungan $kunjungan)
    {
        $validated = $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'foto_base64' => 'nullable|string',
        ]);

        // Verify ownership
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (! $profile || $kunjungan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
            }
        }

        if ($kunjungan->waktu_checkout) {
            return back()->with('error', 'Kunjungan ini sudah di-checkout.');
        }

        // Store photo (file upload from mobile or base64 from desktop webcam)
        $fotoPath = $this->storePhoto($request);

        $kunjungan->update([
            'waktu_checkout' => now(),
            'latitude_checkout' => $validated['latitude'] ?? null,
            'longitude_checkout' => $validated['longitude'] ?? null,
            'foto_checkout' => $fotoPath,
            'status' => 'checkout',
        ]);

        return redirect()->route('minyak.kunjungan.index')
            ->with('success', 'Check-out berhasil dicatat. Kunjungan selesai.');
    }
}
