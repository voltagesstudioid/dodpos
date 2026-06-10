<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .ci-page { max-width:32rem; margin:0 auto; padding:1.5rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .ci-back { display:inline-flex; align-items:center; gap:0.375rem; font-size:0.8125rem; font-weight:600; color:#64748b; text-decoration:none; margin-bottom:1.25rem; transition:color 0.2s; }
        .ci-back:hover { color:#334155; }

        /* Header */
        .ci-hdr {
            background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 50%,#3b82f6 100%);
            border-radius:20px; padding:1.75rem; margin-bottom:1.5rem;
            box-shadow:0 12px 40px rgba(37,99,235,0.25); position:relative; overflow:hidden;
        }
        .ci-hdr::after { content:''; position:absolute; top:-40px; right:-40px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,0.07); }
        .ci-hdr-title { font-size:1.375rem; font-weight:800; color:#fff; letter-spacing:-0.03em; position:relative; z-index:1; }
        .ci-hdr-sub { font-size:0.8125rem; color:rgba(255,255,255,0.75); margin-top:0.25rem; position:relative; z-index:1; }

        /* Card */
        .ci-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px;
            margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .ci-card-hdr {
            padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9;
            display:flex; align-items:center; gap:0.625rem;
        }
        .ci-card-ico {
            width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            font-size:0.9rem; flex-shrink:0;
        }
        .ci-card-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .ci-card-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .ci-card-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .ci-card-lbl { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .ci-card-body { padding:1.25rem; }

        /* Fields */
        .ci-fld { margin-bottom:1rem; }
        .ci-fld:last-child { margin-bottom:0; }
        .ci-lbl { display:block; font-size:0.75rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem; }
        .ci-lbl .req { color:#ef4444; margin-left:2px; }
        .ci-sel {
            width:100%; padding:0.75rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.875rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.625rem center; background-size:16px;
            padding-right:2.25rem;
        }
        .ci-sel:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .ci-textarea {
            width:100%; padding:0.75rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.875rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; resize:vertical; min-height:3.5rem;
        }
        .ci-textarea:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }

        /* GPS Status */
        .ci-gps {
            display:flex; align-items:center; gap:0.5rem; padding:0.75rem 1rem;
            border-radius:10px; font-size:0.8125rem; font-weight:600;
        }
        .ci-gps.loading { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
        .ci-gps.success { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .ci-gps.error { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
        .ci-gps-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .ci-gps.loading .ci-gps-dot { background:#94a3b8; animation:ci-blink 1.5s infinite; }
        .ci-gps.success .ci-gps-dot { background:#10b981; }
        .ci-gps.error .ci-gps-dot { background:#ef4444; }
        @keyframes ci-blink { 0%,100% { opacity:1; } 50% { opacity:0.3; } }

        /* Photo capture */
        .ci-preview-box {
            position:relative; width:100%; aspect-ratio:4/3; background:#f1f5f9; border:2px dashed #cbd5e1;
            border-radius:14px; overflow:hidden; display:flex; align-items:center; justify-content:center;
        }
        .ci-preview-box img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
        .ci-preview-placeholder { text-align:center; color:#94a3b8; display:flex; flex-direction:column; align-items:center; gap:8px; }
        .ci-placeholder-ico {
            width:48px; height:48px; background:#e2e8f0; border-radius:50%; display:flex; align-items:center; justify-content:center;
        }
        .ci-preview-placeholder p { font-size:0.8125rem; margin:0; line-height:1.5; }
        .ci-preview-placeholder strong { color:#64748b; }

        .ci-camera-btn {
            display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; width:100%;
            padding:0.75rem; border-radius:10px; font-size:0.875rem; font-weight:700;
            border:1.5px solid #bfdbfe; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#eff6ff; color:#2563eb; margin-top:0.75rem;
        }
        .ci-camera-btn:hover { background:#dbeafe; border-color:#93c5fd; }

        /* Submit */
        .ci-submit-bar { display:flex; gap:0.75rem; margin-top:1.5rem; }
        .ci-btn-submit {
            flex:1; display:inline-flex; align-items:center; justify-content:center; gap:0.5rem;
            padding:0.9375rem 1.5rem; border-radius:14px; font-size:0.9375rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.25s; font-family:inherit;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .ci-btn-submit:hover { transform:translateY(-2px); box-shadow:0 12px 36px rgba(37,99,235,0.4); }
        .ci-btn-submit:disabled { opacity:0.5; cursor:not-allowed; transform:none; box-shadow:none; }
        .ci-btn-cancel {
            display:inline-flex; align-items:center; justify-content:center; gap:0.375rem;
            padding:0.9375rem 1.5rem; border-radius:14px; font-size:0.9375rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#fff; color:#64748b; text-decoration:none;
        }
        .ci-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }

        /* Error */
        .ci-err { font-size:0.75rem; color:#ef4444; margin-top:0.375rem; font-weight:500; }

        /* Alerts */
        .ci-alert { display:flex; align-items:flex-start; gap:0.625rem; padding:0.875rem 1.125rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.8125rem; font-weight:600; }
        .ci-alert-error { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

        /* Active visit banner */
        .ci-active {
            background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1px solid #fde68a; border-radius:14px;
            padding:1rem 1.25rem; margin-bottom:1.25rem;
        }
        .ci-active-title { font-size:0.8125rem; font-weight:700; color:#92400e; display:flex; align-items:center; gap:0.5rem; }
        .ci-active-body { font-size:0.8125rem; color:#b45309; margin-top:0.375rem; }
        .ci-active-link {
            display:inline-flex; align-items:center; gap:0.375rem; margin-top:0.625rem;
            padding:0.5rem 1rem; border-radius:8px; font-size:0.75rem; font-weight:700;
            background:#f59e0b; color:#fff; text-decoration:none; transition:all 0.2s;
        }
        .ci-active-link:hover { background:#d97706; }

        @media(max-width:640px) { .ci-submit-bar { flex-direction:column; } }
    </style>
    @endpush

    <div class="ci-page">
        {{-- Back --}}
        <a href="{{ route('mineral.kunjungan.index') }}" class="ci-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        {{-- Header --}}
        <div class="ci-hdr">
            <div class="ci-hdr-title">Check-in Kunjungan</div>
            <div class="ci-hdr-sub">Catat kunjungan ke pelanggan dengan foto dan lokasi</div>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="ci-alert ci-alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Active Visit Warning --}}
        @if($activeVisit)
            <div class="ci-active">
                <div class="ci-active-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    Kunjungan Aktif
                </div>
                <div class="ci-active-body">
                    Anda masih memiliki kunjungan aktif ke <strong>{{ $activeVisit->pelanggan->nama_toko ?? '-' }}</strong> sejak {{ $activeVisit->waktu_checkin->format('H:i') }}.
                </div>
                <a href="{{ route('mineral.kunjungan.show', $activeVisit) }}" class="ci-active-link">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat Kunjungan
                </a>
            </div>
        @endif

        <form method="POST" action="{{ route('mineral.kunjungan.checkin.store') }}" enctype="multipart/form-data" id="form-checkin">
            @csrf

            {{-- Pilih Pelanggan --}}
            <div class="ci-card">
                <div class="ci-card-hdr">
                    <div class="ci-card-ico blue">
                        <svg width="16" height="16" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div class="ci-card-lbl">Pilih Pelanggan</div>
                </div>
                <div class="ci-card-body">
                    <div class="ci-fld">
                        <label class="ci-lbl">Pelanggan <span class="req">*</span></label>
                        <select name="pelanggan_id" class="ci-sel" required>
                            <option value="">— Pilih Pelanggan —</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_toko }} — {{ $p->nama_pemilik }}
                                </option>
                            @endforeach
                        </select>
                        @error('pelanggan_id')<div class="ci-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- GPS Location --}}
            <div class="ci-card">
                <div class="ci-card-hdr">
                    <div class="ci-card-ico green">
                        <svg width="16" height="16" fill="none" stroke="#059669" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="ci-card-lbl">Lokasi GPS</div>
                </div>
                <div class="ci-card-body">
                    <input type="hidden" name="latitude" id="gps-lat">
                    <input type="hidden" name="longitude" id="gps-lng">
                    <div class="ci-gps loading" id="gps-status">
                        <div class="ci-gps-dot"></div>
                        <span id="gps-text">Mendeteksi lokasi...</span>
                    </div>
                </div>
            </div>

            {{-- Foto --}}
            <div class="ci-card">
                <div class="ci-card-hdr">
                    <div class="ci-card-ico amber">
                        <svg width="16" height="16" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </div>
                    <div class="ci-card-lbl">Foto Kunjungan</div>
                </div>
                <div class="ci-card-body">
                    <input type="file" name="foto" id="foto-input" accept="image/*" capture="user" style="display:none;">
                    <input type="hidden" name="foto_base64" id="foto-base64">
                    <div class="ci-preview-box" id="preview-box">
                        <video id="webcam-video" autoplay playsinline style="display:none; position:absolute; inset:0; width:100%; height:100%; object-fit:cover;"></video>
                        <canvas id="webcam-canvas" style="display:none;"></canvas>
                        <img id="foto-preview" src="" alt="" style="display:none;">
                        <div class="ci-preview-placeholder" id="foto-placeholder">
                            <div class="ci-placeholder-ico">
                                <svg width="24" height="24" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                            </div>
                            <p>Tekan tombol di bawah untuk<br><strong>ambil foto lokasi kunjungan</strong></p>
                        </div>
                    </div>
                    <button type="button" class="ci-camera-btn" id="btn-camera">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        Buka Kamera
                    </button>
                    <button type="button" class="ci-camera-btn" id="btn-capture" style="display:none; background:#dcfce7; color:#166534; border-color:#86efac;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Ambil Foto
                    </button>
                    @error('foto')<div class="ci-err">{{ $message }}</div>@enderror
                    @error('foto_base64')<div class="ci-err">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Catatan --}}
            <div class="ci-card">
                <div class="ci-card-hdr">
                    <div class="ci-card-ico blue">
                        <svg width="16" height="16" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div class="ci-card-lbl">Catatan</div>
                </div>
                <div class="ci-card-body">
                    <div class="ci-fld">
                        <textarea name="catatan" class="ci-textarea" placeholder="Catatan kunjungan (opsional)...">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="ci-submit-bar">
                <a href="{{ route('mineral.kunjungan.index') }}" class="ci-btn-cancel">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </a>
                <button type="submit" class="ci-btn-submit">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Check-in Sekarang
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fotoInput = document.getElementById('foto-input');
        const fotoPreview = document.getElementById('foto-preview');
        const fotoPlaceholder = document.getElementById('foto-placeholder');
        const fotoBase64 = document.getElementById('foto-base64');
        const btnCamera = document.getElementById('btn-camera');
        const btnCapture = document.getElementById('btn-capture');
        const webcamVideo = document.getElementById('webcam-video');
        const webcamCanvas = document.getElementById('webcam-canvas');
        let webcamStream = null;

        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        function showPreview(src) {
            fotoPreview.src = src;
            fotoPreview.style.display = 'block';
            fotoPlaceholder.style.display = 'none';
            webcamVideo.style.display = 'none';
            btnCapture.style.display = 'none';
            btnCamera.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 4v6h6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg> Ulangi Foto';
        }

        function stopWebcam() {
            if (webcamStream) {
                webcamStream.getTracks().forEach(t => t.stop());
                webcamStream = null;
            }
        }

        async function startDesktopCamera() {
            try {
                webcamStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } } });
                webcamVideo.srcObject = webcamStream;
                webcamVideo.style.display = 'block';
                fotoPreview.style.display = 'none';
                fotoPlaceholder.style.display = 'none';
                btnCamera.style.display = 'none';
                btnCapture.style.display = 'inline-flex';
            } catch (e) {
                alert('Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.');
            }
        }

        btnCamera.addEventListener('click', function() {
            if (isMobile) {
                fotoInput.click();
            } else {
                startDesktopCamera();
            }
        });

        btnCapture.addEventListener('click', function() {
            webcamCanvas.width = webcamVideo.videoWidth;
            webcamCanvas.height = webcamVideo.videoHeight;
            webcamCanvas.getContext('2d').drawImage(webcamVideo, 0, 0);
            const dataUrl = webcamCanvas.toDataURL('image/jpeg', 0.8);
            fotoBase64.value = dataUrl;
            stopWebcam();
            showPreview(dataUrl);
        });

        fotoInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            if (!file) return;
            showPreview(URL.createObjectURL(file));
        });

        // GPS detection
        const gpsStatus = document.getElementById('gps-status');
        const gpsText = document.getElementById('gps-text');
        const gpsLat = document.getElementById('gps-lat');
        const gpsLng = document.getElementById('gps-lng');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    gpsLat.value = pos.coords.latitude;
                    gpsLng.value = pos.coords.longitude;
                    gpsStatus.className = 'ci-gps success';
                    gpsText.textContent = 'Lokasi terdeteksi: ' + pos.coords.latitude.toFixed(6) + ', ' + pos.coords.longitude.toFixed(6);
                },
                function(err) {
                    gpsStatus.className = 'ci-gps error';
                    gpsText.textContent = 'Gagal mendeteksi lokasi. Pastikan GPS aktif.';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        } else {
            gpsStatus.className = 'ci-gps error';
            gpsText.textContent = 'Browser tidak mendukung GPS.';
        }

        // Prevent double submit
        document.getElementById('form-checkin').addEventListener('submit', function(e) {
            const btn = this.querySelector('.ci-btn-submit');
            btn.style.opacity = '0.7';
            btn.style.cursor = 'wait';
        });
    });
    </script>
    @endpush
</x-app-layout>
