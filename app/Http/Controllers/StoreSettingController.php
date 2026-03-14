<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StoreSettingController extends Controller
{
    public function edit()
    {
        $setting = StoreSetting::current();

        return view('pengaturan.toko', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = StoreSetting::current();

        $validated = $request->validate([
            'store_name' => 'required|string|max:120',
            'store_phone' => 'nullable|string|max:50',
            'store_email' => 'nullable|email|max:120',
            'store_address' => 'nullable|string|max:500',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_holder' => 'nullable|string|max:100',
            'timezone' => 'required|string|max:64',
            'currency_symbol' => 'required|string|max:10',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'rounding_mode' => 'required|in:none,nearest_100,nearest_500,nearest_1000',
            'receipt_header' => 'nullable|string|max:1000',
            'receipt_footer' => 'nullable|string|max:1000',
            'fingerprint_ip' => 'nullable|string|max:45',
            'fingerprint_port' => 'nullable|string|max:10',
            'sdm_work_start_time' => 'nullable|date_format:H:i',
            'sdm_work_end_time' => 'nullable|date_format:H:i',
            'sdm_late_grace_minutes' => 'nullable|integer|min:0|max:600',
            'sdm_overtime_rate_per_hour' => 'nullable|numeric|min:0',
            'sdm_late_meal_cut_mode' => 'nullable|in:none,full,percent,fixed',
            'sdm_late_meal_cut_value' => 'nullable|numeric|min:0',
            'sdm_working_days_mode' => 'nullable|in:mon_sat,mon_fri',
            'sdm_calendar_mode' => 'nullable|in:auto,manual',
            'logo' => 'nullable|file|mimes:png,jpg,jpeg,webp|max:2048',
            'remove_logo' => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_logo') && $setting->logo_path) {
            $fullPath = public_path($setting->logo_path);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
            $setting->logo_path = null;
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $dir = public_path('uploads/store');
            File::ensureDirectoryExists($dir);

            $filename = 'logo-'.now()->format('YmdHis').'-'.substr(sha1((string) microtime(true)), 0, 8).'.'.$file->getClientOriginalExtension();
            $file->move($dir, $filename);

            if ($setting->logo_path) {
                $oldPath = public_path($setting->logo_path);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $setting->logo_path = 'uploads/store/'.$filename;
        }

        $setting->fill([
            'store_name' => $validated['store_name'],
            'store_phone' => $validated['store_phone'] ?? null,
            'store_email' => $validated['store_email'] ?? null,
            'store_address' => $validated['store_address'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'bank_account_number' => $validated['bank_account_number'] ?? null,
            'bank_account_holder' => $validated['bank_account_holder'] ?? null,
            'timezone' => $validated['timezone'],
            'currency_symbol' => $validated['currency_symbol'],
            'tax_rate' => $validated['tax_rate'],
            'rounding_mode' => $validated['rounding_mode'],
            'receipt_header' => $validated['receipt_header'] ?? null,
            'receipt_footer' => $validated['receipt_footer'] ?? null,
            'fingerprint_ip' => $validated['fingerprint_ip'] ?? null,
            'fingerprint_port' => $validated['fingerprint_port'] ?? '4370',
            'sdm_work_start_time' => $validated['sdm_work_start_time'] ?? ($setting->sdm_work_start_time ?? '08:00'),
            'sdm_work_end_time' => $validated['sdm_work_end_time'] ?? ($setting->sdm_work_end_time ?? '17:00'),
            'sdm_late_grace_minutes' => $validated['sdm_late_grace_minutes'] ?? ($setting->sdm_late_grace_minutes ?? 10),
            'sdm_overtime_rate_per_hour' => $validated['sdm_overtime_rate_per_hour'] ?? ($setting->sdm_overtime_rate_per_hour ?? 0),
            'sdm_late_meal_cut_mode' => $validated['sdm_late_meal_cut_mode'] ?? ($setting->sdm_late_meal_cut_mode ?? 'full'),
            'sdm_late_meal_cut_value' => $validated['sdm_late_meal_cut_value'] ?? ($setting->sdm_late_meal_cut_value ?? 0),
            'sdm_working_days_mode' => $validated['sdm_working_days_mode'] ?? ($setting->sdm_working_days_mode ?? 'mon_sat'),
            'sdm_calendar_mode' => $validated['sdm_calendar_mode'] ?? ($setting->sdm_calendar_mode ?? 'auto'),
        ])->save();

        return redirect()->route('pengaturan.toko')->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}
