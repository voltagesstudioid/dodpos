<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Attendance;
use App\Models\StoreSetting;
use Carbon\Carbon;

class AdmsController extends Controller
{
    /**
     * Handshake awal dari mesin ZKTeco (saat baru menyala atau sinkronisasi setting)
     */
    public function init(Request $request)
    {
        $sn = $request->query('SN');
        
        Log::info("ADMS Init request from SN: {$sn}");

        // Return OK agar mesin tahu server siap
        // Beberapa mesin butuh konfigurasi seperti GET OPTION FROM: xxx, 
        // tapi OK sudah cukup untuk membiarkannya mengirim data.
        return response('OK', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Heartbeat / polling dari mesin (biasanya untuk cek apakah ada command dari server)
     */
    public function getRequest(Request $request)
    {
        // Karena kita belum mengimplementasikan 2-way command (seperti remote open door atau remote clear data),
        // kita cukup kembalikan OK saja.
        return response('OK', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Mesin mengirim log data (ATTLOG, OPERLOG, dll)
     */
    public function receive(Request $request)
    {
        $sn = $request->query('SN');
        $table = $request->query('table');
        
        $body = $request->getContent(); // Raw body berupa teks tab-separated (TSV)

        // Kita hanya peduli pada data absensi (ATTLOG)
        if ($table !== 'ATTLOG') {
            return response('OK', 200)->header('Content-Type', 'text/plain');
        }

        if (empty(trim($body))) {
            return response('OK', 200)->header('Content-Type', 'text/plain');
        }

        $lines = explode("\n", $body);
        $recordCount = 0;

        $setting = StoreSetting::current();
        $workStartTime = $setting->sdm_work_start_time ?? '08:00';
        $lateGraceMinutes = (int) ($setting->sdm_late_grace_minutes ?? 10);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Format bawaan ADMS ZKTeco: 
            // PIN \t DateTime \t Status \t VerifyType \t WorkCode \t ...
            $parts = preg_split('/\s+/', $line);
            
            if (count($parts) < 3) continue; // Invalid format

            $fingerprintId = $parts[0];
            $datetimeStr = $parts[1] . ' ' . $parts[2]; // misal: "2023-10-10 08:00:00"

            try {
                $scanTime = Carbon::parse($datetimeStr);
                $date = $scanTime->toDateString();
                $time = $scanTime->toTimeString();

                // Cek User yang punya fingerprint_id ini
                $user = User::where('fingerprint_id', $fingerprintId)->first();
                $userId = $user ? $user->id : null;

                // Cari apakah sudah ada record absensi untuk UID + Tanggal ini
                $attendance = Attendance::where('fingerprint_id', $fingerprintId)
                    ->where('date', $date)
                    ->first();

                if (!$attendance) {
                    $lateMinutes = $this->lateMinutes($date, $time, $workStartTime, $lateGraceMinutes);
                    $status = $lateMinutes > 0 ? 'late' : 'present';

                    // Buat Check-In baru
                    Attendance::create([
                        'user_id' => $userId,
                        'fingerprint_id' => $fingerprintId,
                        'date' => $date,
                        'check_in_time' => $time,
                        'check_out_time' => null,
                        'status' => $status,
                        'late_minutes' => $lateMinutes > 0 ? $lateMinutes : 0,
                        'overtime_minutes' => null,
                        'work_hours' => null,
                    ]);
                    $recordCount++;
                } else {
                    $updates = [];
                    $isUpdated = false;

                    // Update user id jika null
                    if ($attendance->user_id === null && $userId !== null) {
                        $updates['user_id'] = $userId;
                        $isUpdated = true;
                    }

                    // Update Check In jika lebih awal
                    if ($attendance->check_in_time && $time < $attendance->check_in_time) {
                        $updates['check_in_time'] = $time;
                        $isUpdated = true;
                    }
                    // Update Check Out jika lebih akhir
                    elseif ($time > $attendance->check_in_time) {
                        if (!$attendance->check_out_time || $time > $attendance->check_out_time) {
                            $updates['check_out_time'] = $time;

                            $checkIn = Carbon::parse($date . ' ' . ($updates['check_in_time'] ?? $attendance->check_in_time));
                            $checkOut = Carbon::parse($date . ' ' . $time);
                            $updates['work_hours'] = $checkIn->diffInMinutes($checkOut) / 60;

                            $isUpdated = true;
                        }
                    }

                    if ($isUpdated) {
                        $finalCheckIn = $updates['check_in_time'] ?? $attendance->check_in_time;
                        if ($finalCheckIn) {
                            $lateMinutes = $this->lateMinutes($date, $finalCheckIn, $workStartTime, $lateGraceMinutes);
                            $updates['late_minutes'] = $lateMinutes > 0 ? $lateMinutes : 0;
                            if (in_array($attendance->status, ['present', 'late'], true)) {
                                $updates['status'] = $lateMinutes > 0 ? 'late' : 'present';
                            }
                        }

                        $attendance->update($updates);
                        $recordCount++;
                    }
                }

            } catch (\Exception $e) {
                Log::error("ADMS Parse Error on line: {$line}", ['error' => $e->getMessage()]);
                continue;
            }
        }

        // Response wajib dari ZKTeco Protocol saat terima log adalah "OK: [jumlah]"
        return response("OK: {$recordCount}", 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Hitung keterlambatan berdasarkan jam masuk dan toleransi
     */
    private function lateMinutes(string $date, string $checkInTime, string $workStartTime, int $graceMinutes): int
    {
        $in = Carbon::parse($date . ' ' . $checkInTime);
        $start = Carbon::parse($date . ' ' . $workStartTime);
        $startWithGrace = $start->copy()->addMinutes($graceMinutes);

        if ($in->greaterThan($startWithGrace)) {
            return $start->diffInMinutes($in); // Dihitung dari jam masuk normal, bukan dari batas toleransi
        }
        return 0;
    }
}
