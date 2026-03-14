<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $penggajian->user->name }} - {{ $monthName }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            background: #f1f5f9;
        }
        .slip-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        @media print {
            body { background: transparent; padding: 0; }
            .slip-container { box-shadow: none; border: none; margin: 0; }
            .no-print { display: none !important; }
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; color: #666; }
        
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-table td { padding: 4px 0; }
        .info-table td:nth-child(1) { width: 120px; font-weight: bold; }
        .info-table td:nth-child(2) { width: 10px; }
        
        .rincian-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
            margin-top: 20px;
        }
        
        .calc-table {
            width: 100%;
            border-collapse: collapse;
        }
        .calc-table td { padding: 6px 0; }
        .calc-table .amount { text-align: right; }
        .calc-table .subtext { font-size: 12px; color: #666; padding-left: 15px; }
        
        .total-row {
            border-top: 2px dashed #000;
            font-weight: bold;
            font-size: 16px;
        }
        .total-row td { padding-top: 10px; }
        
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature-box { width: 200px; }
        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
        }
        
        .btn-print {
            display: block;
            width: 100%;
            max-width: 600px;
            margin: 0 auto 20px;
            padding: 10px;
            background: #2563eb;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .btn-print:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <button class="no-print btn-print" onclick="window.print()">🖨️ Cetak Slip Gaji</button>
    <div class="slip-container">
        <div class="header">
            <h1>Bukti Pembayaran Gaji</h1>
            <p>Periode: <strong>{{ $monthName }}</strong></p>
        </div>
        
        <table class="info-table">
            <tr>
                <td>Nama Karyawan</td><td>:</td>
                <td>{{ $penggajian->user->name }}</td>
            </tr>
            <tr>
                <td>Jabatan / Role</td><td>:</td>
                <td>{{ $penggajian->user->role ?? '-' }}</td>
            </tr>
        </table>
        
        <div class="rincian-title">PENERIMAAN</div>
        @php
            $bonusSum = isset($bonuses) ? (float) $bonuses->sum('amount') : 0.0;
            $incentiveAmount = (float) ($penggajian->incentive_amount ?? 0);
            $showBonusBreakdown = isset($bonuses) && $bonuses->count() > 0 && abs($incentiveAmount - $bonusSum) < 0.01;
        @endphp
        <table class="calc-table">
            <tr>
                <td>Gaji Pokok</td>
                <td class="amount">Rp {{ number_format($penggajian->total_basic_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Uang Makan (Bersih)</td>
                <td class="amount">Rp {{ number_format($penggajian->total_allowance, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="subtext">
                    ({{ $penggajian->total_attendance }} Hari x Rp {{ number_format($penggajian->meal_allowance_per_day ?? ($penggajian->user->employee->daily_allowance ?? 0), 0, ',', '.') }})
                    @if(($penggajian->late_meal_penalty ?? 0) > 0)
                        - Potongan Telat Rp {{ number_format($penggajian->late_meal_penalty, 0, ',', '.') }}
                    @endif
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Lembur</td>
                <td class="amount">Rp {{ number_format($penggajian->overtime_pay ?? 0, 0, ',', '.') }}</td>
            </tr>
            @if($showBonusBreakdown)
                @foreach($bonuses as $b)
                <tr>
                    <td>{{ $b->description }} (Bonus)</td>
                    <td class="amount">Rp {{ number_format($b->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endif
            @if(! $showBonusBreakdown && ($penggajian->incentive_amount ?? 0) > 0)
                <tr>
                    <td>Insentif</td>
                    <td class="amount">Rp {{ number_format($penggajian->incentive_amount ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td>Bonus Performa</td>
                <td class="amount">Rp {{ number_format($penggajian->performance_bonus ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: bold;">
                <td style="padding-top:10px;">Total Penerimaan</td>
                <td class="amount" style="padding-top:10px;">
                    Rp {{ number_format(
                        ($penggajian->total_basic_salary ?? 0)
                        + ($penggajian->total_allowance ?? 0)
                        + ($penggajian->overtime_pay ?? 0)
                        + ($penggajian->incentive_amount ?? 0)
                        + ($penggajian->performance_bonus ?? 0),
                        0, ',', '.'
                    ) }}
                </td>
            </tr>
        </table>
        
        <div class="rincian-title" style="color: #dc2626;">POTONGAN</div>
        <table class="calc-table">
            @if(($penggajian->absence_deduction ?? 0) > 0)
                <tr>
                    <td>Potongan Tidak Hadir / Tidak Absen</td>
                    <td class="amount" style="color: #dc2626;">Rp {{ number_format($penggajian->absence_deduction, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if(isset($deductions) && $deductions->count() > 0)
                @foreach($deductions as $d)
                <tr>
                    <td>{{ $d->description }} ({{ $d->date->format('d/m') }})</td>
                    <td class="amount" style="color: #dc2626;">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td><em>Tidak ada potongan bulan ini.</em></td>
                    <td class="amount">Rp 0</td>
                </tr>
            @endif
            <tr style="font-weight: bold; color: #dc2626;">
                <td style="padding-top:10px;">Total Potongan</td>
                <td class="amount" style="padding-top:10px;">
                    Rp {{ number_format(($penggajian->total_deductions ?? 0) + ($penggajian->absence_deduction ?? 0), 0, ',', '.') }}
                </td>
            </tr>
        </table>
        
        <table class="calc-table" style="margin-top: 20px;">
            <tr class="total-row">
                <td>GAJI BERSIH (TAKE HOME PAY)</td>
                <td class="amount" style="font-size: 18px;">Rp {{ number_format($penggajian->net_salary, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <div class="footer">
            <div class="signature-box">
                <div>Penerima,</div>
                <div class="signature-line"></div>
                <div>{{ $penggajian->user->name }}</div>
            </div>
            <div class="signature-box">
                <div>Penyerah,</div>
                <div class="signature-line"></div>
                <div>( .......................... )</div>
            </div>
        </div>
    </div>
</body>
</html>
