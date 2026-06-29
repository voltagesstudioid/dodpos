<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Ditolak</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;color:#0f172a;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;">
<div style="max-width:560px;margin:0 auto;padding:24px;">
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;padding:20px;">
        <div style="font-weight:900;font-size:18px;margin-bottom:6px;">Pendaftaran Akun Ditolak</div>
        <div style="color:#475569;font-size:14px;line-height:1.6;">
            Halo <b>{{ $name }}</b>, mohon maaf pendaftaran akun Anda ditolak oleh Supervisor.
        </div>

        <div style="margin-top:14px;border-top:1px solid #f1f5f9;padding-top:14px;">
            <div style="font-size:13px;color:#475569;line-height:1.8;">
                <div><span style="color:#94a3b8;">Email:</span> <b>{{ $email }}</b></div>
            </div>
        </div>

        <div style="margin-top:16px;color:#475569;font-size:13px;line-height:1.6;">
            Jika Anda merasa ini adalah kesalahan, silakan hubungi Supervisor atau coba daftar ulang dengan email yang sama.
        </div>
    </div>

    <div style="margin-top:14px;color:#94a3b8;font-size:12px;text-align:center;">
        &copy; {{ date('Y') }} DODPOS
    </div>
</div>
</body>
</html>
