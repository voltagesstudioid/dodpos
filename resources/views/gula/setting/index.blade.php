@extends('layouts.app', ['title' => 'Jenis & Satuan Produk Gula'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .st-page { max-width:56rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    .st-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
    .st-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#b45309; font-size:0.8125rem; font-weight:700; transition:color 0.2s; }
    .st-back:hover { color:#92400e; text-decoration:underline; }
    .st-crumb { font-size:0.8125rem; font-weight:700; color:#78350f; }

    .st-card { background:#fff; border:1px solid #fde68a; border-radius:16px; overflow:hidden; box-shadow:0 1px 4px rgba(120,53,15,0.04); margin-bottom:1.5rem; }
    .st-card-hdr { padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #fde68a; }
    .st-card-hdr-left { display:flex; align-items:center; gap:0.75rem; }
    .st-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .st-card-ico svg { width:17px; height:17px; }
    .st-card-title { font-size:0.875rem; font-weight:700; color:#78350f; }
    .st-card-sub { font-size:0.6875rem; color:#b45309; }
    .st-card-body { padding:1.25rem; }

    .st-card.amber .st-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .st-card.amber .st-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .st-card.green .st-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); border-bottom-color:#bbf7d0; }
    .st-card.green .st-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }

    /* Add Form */
    .st-add-form { display:flex; gap:0.5rem; align-items:flex-end; margin-bottom:1.25rem; }
    .st-add-form .st-fg { flex:1; display:flex; flex-direction:column; gap:0.25rem; }
    .st-add-form .st-fg-sm { width:80px; flex:none; }
    .st-add-form .st-fg label { font-size:0.6875rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.04em; }
    .st-add-inp {
        width:100%; padding:0.5rem 0.75rem; border:1.5px solid #e2e8f0; border-radius:8px;
        background:#fcfcfd; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; box-sizing:border-box;
    }
    .st-add-inp:focus { border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
    .st-add-btn {
        padding:0.5rem 1rem; border-radius:8px; font-size:0.75rem; font-weight:700;
        cursor:pointer; transition:all 0.2s; border:none; font-family:inherit; white-space:nowrap;
        background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 2px 8px rgba(217,119,6,0.25);
    }
    .st-add-btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(217,119,6,0.35); }

    /* Table */
    .st-tbl { width:100%; border-collapse:separate; border-spacing:0; }
    .st-tbl th {
        text-align:left; padding:0.625rem 0.875rem; font-size:0.6875rem; font-weight:700;
        text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; background:#fffbeb;
        border-bottom:1px solid #fde68a;
    }
    .st-tbl td {
        padding:0.75rem 0.875rem; font-size:0.8125rem; color:#334155; border-bottom:1px solid #f8fafc;
        vertical-align:middle;
    }
    .st-tbl tr:last-child td { border-bottom:none; }
    .st-tbl tr:hover td { background:#fffbeb; }

    /* Badge */
    .st-badge { display:inline-flex; align-items:center; gap:3px; padding:0.2rem 0.5rem; border-radius:6px; font-size:0.6875rem; font-weight:700; }
    .st-badge.aktif { background:#ecfdf5; color:#059669; }
    .st-badge.nonaktif { background:#f1f5f9; color:#64748b; }

    /* Action Buttons */
    .st-actions { display:flex; gap:0.375rem; }
    .st-btn-sm {
        padding:0.3rem 0.625rem; border-radius:6px; font-size:0.6875rem; font-weight:600;
        cursor:pointer; transition:all 0.15s; border:1px solid transparent; font-family:inherit;
        display:inline-flex; align-items:center; gap:3px;
    }
    .st-btn-edit { background:#eff6ff; border-color:#bfdbfe; color:#2563eb; }
    .st-btn-edit:hover { background:#dbeafe; }
    .st-btn-del { background:#fef2f2; border-color:#fecaca; color:#dc2626; }
    .st-btn-del:hover { background:#fee2e2; }

    /* Inline Edit Form */
    .st-edit-form { display:flex; gap:0.375rem; align-items:center; }
    .st-edit-form .st-add-inp { padding:0.375rem 0.625rem; font-size:0.8125rem; }

    .st-empty { text-align:center; padding:2rem; color:#94a3b8; font-size:0.8125rem; font-style:italic; }

    /* Alerts */
    .st-alert { padding:0.75rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:600; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem; }
    .st-alert-success { background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; }
    .st-alert-error { background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; }

    .st-count { font-size:0.6875rem; font-weight:700; color:#94a3b8; background:#f1f5f9; padding:0.2rem 0.5rem; border-radius:6px; }
    .st-shortcut { font-family:'JetBrains Mono',monospace; font-size:0.72rem; color:#b45309; background:#fffbeb; padding:1px 6px; border-radius:4px; border:1px solid #fde68a; }
</style>
@endpush

@section('content')
<div class="st-page">

    <nav class="st-nav">
        <a href="{{ route('gula.produk.index') }}" class="st-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Data Produk
        </a>
        <span style="color:#cbd5e1;font-size:0.8125rem;">/</span>
        <span class="st-crumb">Jenis & Satuan</span>
    </nav>

    @if(session('success'))
        <div class="st-alert st-alert-success">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="st-alert st-alert-error">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ======================== JENIS PRODUK ======================== --}}
    <div class="st-card amber">
        <div class="st-card-hdr">
            <div class="st-card-hdr-left">
                <div class="st-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <div>
                    <div class="st-card-title">Jenis Produk</div>
                    <div class="st-card-sub">Kategori / jenis produk gula</div>
                </div>
            </div>
            <span class="st-count">{{ $jenisList->count() }} item</span>
        </div>
        <div class="st-card-body">
            {{-- Add Form --}}
            <form method="POST" action="{{ route('gula.setting.jenis.store') }}" class="st-add-form">
                @csrf
                <div class="st-fg">
                    <label>Nama Jenis</label>
                    <input type="text" name="nama" class="st-add-inp @error('nama') is-invalid @enderror" placeholder="Contoh: Pasir, Merah, Batu" required value="{{ old('nama') }}">
                    @error('nama')<div style="color:#dc2626;font-size:0.6875rem;">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="st-add-btn">+ Tambah</button>
            </form>

            {{-- Table --}}
            @if($jenisList->count() > 0)
            <table class="st-tbl">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nama Jenis</th>
                        <th style="width:80px;">Produk</th>
                        <th style="width:90px;">Status</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jenisList as $i => $item)
                    <tr id="jenis-row-{{ $item->id }}">
                        <td style="color:#94a3b8;font-weight:600;">{{ $i + 1 }}</td>
                        <td>
                            <span id="jenis-display-{{ $item->id }}">{{ $item->nama }}</span>
                            <form method="POST" action="{{ route('gula.setting.jenis.update', $item) }}" class="st-edit-form" id="jenis-edit-{{ $item->id }}" style="display:none;">
                                @csrf @method('PUT')
                                <input type="text" name="nama" value="{{ $item->nama }}" class="st-add-inp" required>
                                <input type="hidden" name="status" value="{{ $item->status }}">
                                <button type="submit" class="st-btn-sm st-btn-edit" title="Simpan">✓</button>
                                <button type="button" class="st-btn-sm st-btn-del" onclick="document.getElementById('jenis-edit-{{ $item->id }}').style.display='none';document.getElementById('jenis-display-{{ $item->id }}').style.display='inline';" title="Batal">✕</button>
                            </form>
                        </td>
                        <td>{{ $item->produks->count() }}</td>
                        <td>
                            <span class="st-badge {{ $item->status }}">{{ $item->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td>
                            <div class="st-actions">
                                <button type="button" class="st-btn-sm st-btn-edit" onclick="document.getElementById('jenis-display-{{ $item->id }}').style.display='none';document.getElementById('jenis-edit-{{ $item->id }}').style.display='flex';">Edit</button>
                                @if($item->produks->count() === 0)
                                <form method="POST" action="{{ route('gula.setting.jenis.destroy', $item) }}" onsubmit="return confirm('Hapus jenis \'{{ $item->nama }}\'?');" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="st-btn-sm st-btn-del">Hapus</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="st-empty">Belum ada jenis produk. Tambahkan di atas.</div>
            @endif
        </div>
    </div>

    {{-- ======================== SATUAN ======================== --}}
    <div class="st-card green">
        <div class="st-card-hdr">
            <div class="st-card-hdr-left">
                <div class="st-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                </div>
                <div>
                    <div class="st-card-title">Satuan</div>
                    <div class="st-card-sub">Satuan pengukuran untuk produk gula</div>
                </div>
            </div>
            <span class="st-count">{{ $satuanList->count() }} item</span>
        </div>
        <div class="st-card-body">
            {{-- Add Form --}}
            <form method="POST" action="{{ route('gula.setting.satuan.store') }}" class="st-add-form">
                @csrf
                <div class="st-fg">
                    <label>Nama Satuan</label>
                    <input type="text" name="nama" class="st-add-inp @error('nama') is-invalid @enderror" placeholder="Contoh: Kilogram, Liter, Pcs" required value="{{ old('nama') }}">
                    @error('nama')<div style="color:#dc2626;font-size:0.6875rem;">{{ $message }}</div>@enderror
                </div>
                <div class="st-fg st-fg-sm">
                    <label>Singkatan</label>
                    <input type="text" name="singkatan" class="st-add-inp" placeholder="kg" value="{{ old('singkatan') }}">
                </div>
                <button type="submit" class="st-add-btn">+ Tambah</button>
            </form>

            {{-- Table --}}
            @if($satuanList->count() > 0)
            <table class="st-tbl">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nama Satuan</th>
                        <th style="width:80px;">Singkatan</th>
                        <th style="width:80px;">Produk</th>
                        <th style="width:90px;">Status</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($satuanList as $i => $item)
                    <tr id="satuan-row-{{ $item->id }}">
                        <td style="color:#94a3b8;font-weight:600;">{{ $i + 1 }}</td>
                        <td>
                            <span id="satuan-display-{{ $item->id }}">{{ $item->nama }}</span>
                            <form method="POST" action="{{ route('gula.setting.satuan.update', $item) }}" class="st-edit-form" id="satuan-edit-{{ $item->id }}" style="display:none;">
                                @csrf @method('PUT')
                                <input type="text" name="nama" value="{{ $item->nama }}" class="st-add-inp" required>
                                <input type="text" name="singkatan" value="{{ $item->singkatan }}" class="st-add-inp" style="width:70px;" placeholder="Singkatan">
                                <input type="hidden" name="status" value="{{ $item->status }}">
                                <button type="submit" class="st-btn-sm st-btn-edit" title="Simpan">✓</button>
                                <button type="button" class="st-btn-sm st-btn-del" onclick="document.getElementById('satuan-edit-{{ $item->id }}').style.display='none';document.getElementById('satuan-display-{{ $item->id }}').style.display='inline';" title="Batal">✕</button>
                            </form>
                        </td>
                        <td>
                            @if($item->singkatan)<span class="st-shortcut">{{ $item->singkatan }}</span>@else—@endif
                        </td>
                        <td>{{ $item->produks->count() }}</td>
                        <td>
                            <span class="st-badge {{ $item->status }}">{{ $item->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td>
                            <div class="st-actions">
                                <button type="button" class="st-btn-sm st-btn-edit" onclick="document.getElementById('satuan-display-{{ $item->id }}').style.display='none';document.getElementById('satuan-edit-{{ $item->id }}').style.display='flex';">Edit</button>
                                @if($item->produks->count() === 0)
                                <form method="POST" action="{{ route('gula.setting.satuan.destroy', $item) }}" onsubmit="return confirm('Hapus satuan \'{{ $item->nama }}\'?');" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="st-btn-sm st-btn-del">Hapus</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="st-empty">Belum ada satuan. Tambahkan di atas.</div>
            @endif
        </div>
    </div>

</div>
@endsection
