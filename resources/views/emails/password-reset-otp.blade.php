<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode OTP Reset Password</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;color:#0f172a;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;">
<div style="max-width:560px;margin:0 auto;padding:24px;">
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;padding:20px;">
        <div style="font-weight:900;font-size:18px;margin-bottom:6px;">Reset Password</div>
        <div style="color:#475569;font-size:14px;margin-bottom:18px;">
            Halo {{ $name ?: 'Pengguna' }}, berikut kode OTP untuk reset password akun Anda.
        </div>

        <div style="background:#0f172a;color:#ffffff;border-radius:12px;padding:16px 18px;text-align:center;">
            <div style="font-size:12px;letter-spacing:0.18em;opacity:0.7;margin-bottom:8px;">KODE OTP</div>
            <div style="font-size:28px;letter-spacing:0.22em;font-weight:900;">{{ $otp }}</div>
        </div>

        <div style="margin-top:14px;color:#475569;font-size:13px;line-height:1.6;">
            Kode ini berlaku selama {{ $expiresMinutes }} menit. Jangan bagikan kode ini kepada siapa pun.
        </div>

        <div style="margin-top:18px;color:#94a3b8;font-size:12px;line-height:1.6;">
            Jika Anda tidak meminta reset password, abaikan email ini.
        </div>
    </div>

    <div style="margin-top:14px;color:#94a3b8;font-size:12px;text-align:center;">
        © {{ date('Y') }} DODPOS
    </div>
</div>
</body>
</html>

