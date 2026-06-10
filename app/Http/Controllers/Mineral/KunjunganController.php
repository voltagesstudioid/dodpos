<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralKunjungan;
use App\Models\MineralSales;
use App\Models\MineralPelanggan;
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
        return MineralSales::where('user_id', Auth::id())->first();
    }

    /**
     * Auto-fill pelanggan alamat from checkin GPS if address is still empty.
     * Uses Nominatim (OpenStreetMap) for reverse geocoding — no API key needed.
     */
    private function autoFillAlamatPelanggan(int $pelangganId, ?string $lat, ?string $lng): void
    {
        if (empty($lat) || empty($lng)) {
            return;
        }

        $pelanggan = MineralPelanggan::find($pelangganId);
        if (! $pelanggan) {
            return;
        }

        $update = [];

        // Only fill lat/lng if empty
        if (empty($pelanggan->latitude) || empty($pelanggan->longitude)) {
            $update['latitude']  = $lat;
            $update['longitude'] = $lng;
        }

        // Reverse geocode to get address parts (only if alamat is empty)
        if (empty($pelanggan->alamat)) {
            $address = $this->reverseGeocode($lat, $lng);
            if ($address) {
                $update['alamat']    = $address['alamat'] ?? null;
                $update['kecamatan'] = $address['kecamatan'] ?? null;
                $update['kota']      = $address['kota'] ?? null;
            }
        }

        if (! empty($update)) {
            $pelanggan->update($update);
        }
    }

    /**
     * Reverse geocode lat/lng to address using Nominatim (OpenStreetMap).
     * Returns array with 'alamat', 'kecamatan', 'kota' keys, or null on failure.
     */
    private function reverseGeocode(string $lat, string $lng): ?array
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&accept-language=id&addressdetails=1";
            $response = @file_get_contents($url, false, stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'header'  => 'User-Agent: DODPOS/1.0',
                ],
            ]));

            if ($response === false) {
                return null;
            }

            $data = json_decode($response, true);
            if (! $data || ! isset($data['address'])) {
                return null;
            }

            $addr = $data['address'];

            // Build street address
            $streetParts = array_filter([
                $addr['road'] ?? null,
                isset($addr['house_number']) ? $addr['house_number'] : null,
            ]);
            $street = ! empty($streetParts) ? implode(' ', $streetParts) : ($addr['road'] ?? null);

            // Build full alamat with RT/RW info
            $alamatParts = array_filter([$street, $addr['neighbourhood'] ?? null, $addr['suburb'] ?? null]);
            $alamat = ! empty($alamatParts) ? implode(', ', $alamatParts) : null;

            return [
                'alamat'    => $alamat,
                'kecamatan' => $addr['city_district'] ?? $addr['county'] ?? null,
                'kota'      => $addr['city'] ?? $addr['town'] ?? $addr['municipality'] ?? null,
            ];
        } catch (\Throwable $e) {
            // Silent fail — don't block checkin if geocoding fails
            return null;
        }
    }

    private function storePhoto(Request $request): ?string
    {
        if ($request->hasFile('foto')) {
            return $request->file('foto')->store('mineral-kunjungan-photos/' . now()->format('Y-m-d'), 'public');
        }
        if ($request->filled('foto_base64')) {
            $base64 = $request->input('foto_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64)) {
                $base64Data = substr($base64, strpos($base64, ',') + 1);
                $imageData = base64_decode($base64Data);
                if ($imageData !== false) {
                    $dir = 'mineral-kunjungan-photos/' . now()->format('Y-m-d');
                    $filename = 'webcam_' . uniqid() . '.jpg';
                    \Illuminate\Support\Facades\Storage::disk('public')->put($dir . '/' . $filename, $imageData);
                    return $dir . '/' . $filename;
                }
            }
        }
        return null;
    }

    public function index(Request $request)
    {
        $isSalesRole = $this->isSales();
        $tanggalMulai = $request->input('tanggal_mulai', now()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());
        $salesId = $request->input('sales_id');
        $status = $request->input('status');

        // Base query
        $query = MineralKunjungan::query()
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
            $salesList = MineralSales::where('status', 'aktif')->orderBy('nama')->get();
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
        $baseQuery = MineralKunjungan::whereDate('waktu_checkin', '>=', $tanggalMulai)
            ->whereDate('waktu_checkin', '<=', $tanggalSelesai);

        if ($isSalesRole && isset($profile) && $profile) {
            $baseQuery->where('sales_id', $profile->id);
        } elseif ($salesId) {
            $baseQuery->where('sales_id', $salesId);
        }

        // Don't count cancelled visits in stats
        $baseQueryClone = (clone $baseQuery)->whereNotIn('status', ['cancel']);
        $totalKunjungan = $baseQueryClone->count();
        $kunjunganSelesai = (clone $baseQuery)->where('status', 'checkout')->count();
        $kunjunganBertransaksi = (clone $baseQuery)->where('ada_penjualan', true)->count();

        // Calculate average duration in minutes from completed visits
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

        // Kunjungan by sales for chart (scoped to same filters)
        $chartQuery = MineralKunjungan::query()
            ->whereDate('waktu_checkin', '>=', $tanggalMulai)
            ->whereDate('waktu_checkin', '<=', $tanggalSelesai);

        if ($isSalesRole && isset($profile) && $profile) {
            $chartQuery->where('sales_id', $profile->id);
        } elseif ($salesId) {
            $chartQuery->where('sales_id', $salesId);
        }

        $kunjunganBySales = $chartQuery
            ->selectRaw('sales_id, COUNT(*) as total')
            ->groupBy('sales_id')
            ->with('sales')
            ->get();

        return view('mineral.kunjungan.index', compact(
            'kunjungans',
            'stats',
            'salesList',
            'kunjunganBySales',
            'isSalesRole',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }

    public function show(MineralKunjungan $kunjungan)
    {
        $kunjungan->load(['sales', 'pelanggan', 'penjualans']);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (! $profile || $kunjungan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
            }
        }

        // Get the first penjualan linked to this kunjungan
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

        return view('mineral.kunjungan.show', compact('kunjungan', 'isSalesRole'));
    }

    public function checkinForm()
    {
        $pelanggans = MineralPelanggan::where('status', 'aktif')
            ->orderBy('nama_toko')
            ->get();

        $profile = $this->getSalesProfile();
        $activeVisit = null;
        if ($profile) {
            // Find any active visit (checkin status, not cancelled)
            $activeVisit = MineralKunjungan::with('pelanggan')
                ->where('sales_id', $profile->id)
                ->where('status', 'checkin')
                ->whereNull('waktu_checkout')
                ->latest('waktu_checkin')
                ->first();
        }

        return view('mineral.kunjungan.checkin', compact('pelanggans', 'activeVisit'));
    }

    public function storeCheckin(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:mineral_pelanggan,id',
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
        $existing = MineralKunjungan::where('sales_id', $profile->id)
            ->where('pelanggan_id', $validated['pelanggan_id'])
            ->whereDate('waktu_checkin', now()->toDateString())
            ->where('status', 'checkin')
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki kunjungan aktif ke pelanggan ini hari ini.');
        }

        $fotoPath = $this->storePhoto($request);

        // GPS radius validation (warn if > 500m from customer location)
        $gpsWarning = null;
        $pelanggan = MineralPelanggan::find($validated['pelanggan_id']);
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

        MineralKunjungan::create([
            'sales_id' => $profile->id,
            'pelanggan_id' => $validated['pelanggan_id'],
            'waktu_checkin' => now(),
            'latitude_checkin' => $validated['latitude'] ?? null,
            'longitude_checkin' => $validated['longitude'] ?? null,
            'foto_checkin' => $fotoPath,
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'checkin',
        ]);

        // Auto-fill alamat pelanggan jika masih kosong
        $this->autoFillAlamatPelanggan($validated['pelanggan_id'], $validated['latitude'] ?? null, $validated['longitude'] ?? null);

        $message = 'Check-in berhasil dicatat.';
        if ($gpsWarning) {
            $message .= ' ' . $gpsWarning;
        }

        return redirect()->route('mineral.kunjungan.index')
            ->with('success', $message);
    }

    /**
     * Cancel an active kunjungan (soft cancel — sets status='cancel').
     */
    public function cancel(MineralKunjungan $kunjungan)
    {
        // Only allow cancellation by owner or admin
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (! $profile || $kunjungan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
            }
        }

        // Can only cancel visits that are still active (checkin status)
        if ($kunjungan->status === 'checkout') {
            return back()->with('error', 'Kunjungan yang sudah selesai tidak bisa dibatalkan.');
        }

        if ($kunjungan->status === 'cancel') {
            return back()->with('error', 'Kunjungan ini sudah dibatalkan sebelumnya.');
        }

        $kunjungan->update([
            'status' => 'cancel',
            'ada_penjualan' => false,
        ]);

        return redirect()->route('mineral.kunjungan.index')
            ->with('success', 'Kunjungan berhasil dibatalkan.');
    }

    public function storeCheckout(Request $request, MineralKunjungan $kunjungan)
    {
        $validated = $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'foto_base64' => 'nullable|string',
        ]);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (! $profile || $kunjungan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
            }
        }

        if ($kunjungan->waktu_checkout) {
            return back()->with('error', 'Kunjungan ini sudah di-checkout.');
        }

        $fotoPath = $this->storePhoto($request);

        $kunjungan->update([
            'waktu_checkout' => now(),
            'latitude_checkout' => $validated['latitude'] ?? null,
            'longitude_checkout' => $validated['longitude'] ?? null,
            'foto_checkout' => $fotoPath,
            'status' => 'checkout',
        ]);

        return redirect()->route('mineral.kunjungan.index')
            ->with('success', 'Check-out berhasil dicatat. Kunjungan selesai.');
    }
}
