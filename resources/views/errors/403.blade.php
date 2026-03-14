<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#f1f5f9; color:#0f172a; margin:0; }
        .wrap { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1.5rem; }
        .card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.5rem; max-width:680px; width:100%; box-shadow:0 10px 30px rgba(2,6,23,0.05); }
        .header { display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem; }
        .badge { background:#fef3c7; color:#a16207; border:1px solid #fde68a; padding:0.25rem 0.5rem; border-radius:999px; font-weight:700; font-size:0.75rem; }
        .title { font-weight:800; font-size:1.35rem; margin:0; }
        .subtitle { color:#64748b; margin:0.25rem 0 0.75rem; font-size:0.92rem; }
        .list { background:#f8fafc; border:1px solid #e2e8f0; padding:0.75rem 1rem; border-radius:12px; margin-top:0.75rem; }
        .list h4 { margin:0 0 0.5rem; font-size:0.85rem; color:#334155; }
        .list ul { margin:0; padding-left:1.25rem; color:#475569; font-size:0.87rem; }
        .actions { display:flex; gap:0.5rem; margin-top:1rem; flex-wrap:wrap; }
        .btn { display:inline-flex; align-items:center; gap:0.4rem; text-decoration:none; padding:0.5rem 0.95rem; border-radius:8px; font-weight:700; font-size:0.85rem; border:1px solid #e2e8f0; }
        .btn-primary { background:#4f46e5; color:#fff; border-color:#4f46e5; }
        .btn-secondary { background:#fff; color:#475569; }
    </style>
    <script>
        function goBack(){ if (history.length > 1) history.back(); else window.location = '/'; }
    </script>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="header">
                <span class="badge">403</span>
                <h1 class="title">Akses Ditolak</h1>
            </div>
            <p class="subtitle">Anda tidak memiliki izin untuk membuka halaman atau melakukan aksi ini.</p>
            <div class="list">
                <h4>Izin yang mungkin diperlukan</h4>
                <ul>
                    <li>SDM: view_absensi, view_karyawan</li>
                    <li>Operasional Kategori: view_kategori_operasional, create/edit/delete_kategori_operasional</li>
                    <li>Operasional Kendaraan: view_kendaraan_operasional, create/edit/delete_kendaraan_operasional</li>
                    <li>POS & Gudang: izin sesuai menu yang diakses</li>
                </ul>
            </div>
            <div class="actions">
                <button class="btn btn-primary" onclick="goBack()">← Kembali</button>
                <a class="btn btn-secondary" href="/">🏠 Dashboard</a>
                <a class="btn btn-secondary" href="/profile">⚙️ Profil</a>
            </div>
        </div>
    </div>
</body>
</html>

