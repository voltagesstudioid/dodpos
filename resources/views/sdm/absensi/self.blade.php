<x-app-layout>
    <x-slot name="header">Absen Saya</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-absen-container">

            {{-- ALERTS --}}
            @if(session('success')) 
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    {{ session('success') }}
                </div> 
            @endif
            @if(session('error'))   
                <div class="tr-alert tr-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') }}
                </div>   
            @endif

            {{-- ─── STATUS CARD ─── --}}
            <div class="tr-status-card">
                <div class="tr-status-header">
                    <div class="tr-user-info">
                        <div class="tr-avatar-placeholder">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <div class="tr-user-name">{{ $user->name }}</div>
                    </div>
                    <div class="tr-date-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d M Y') }}
                    </div>
                </div>

                <div class="tr-status-grid">
                    <div class="tr-status-cell">
                        <div class="tr-cell-label">Jam Masuk</div>
                        <div class="tr-cell-value {{ !$attendance?->check_in_time ? 'tr-muted' : '' }}">
                            {{ $attendance?->check_in_time ?? '—' }}
                        </div>
                    </div>
                    <div class="tr-status-cell">
                        <div class="tr-cell-label">Jam Pulang</div>
                        <div class="tr-cell-value {{ !$attendance?->check_out_time ? 'tr-muted' : '' }}">
                            {{ $attendance?->check_out_time ?? '—' }}
                        </div>
                    </div>
                    <div class="tr-status-cell tr-highlight-cell">
                        <div class="tr-cell-label">Total Jam</div>
                        <div class="tr-cell-value {{ !$attendance?->work_hours ? 'tr-muted' : 'tr-text-primary' }}">
                            {{ $attendance?->work_hours ? number_format($attendance->work_hours, 1).'j' : '—' }}
                        </div>
                    </div>
                </div>

                <div class="tr-status-footer">
                    @php
                        $st = $attendance?->status;
                        $badgeCls = match($st) {
                            'present' => 'tr-badge-success',
                            'late'    => 'tr-badge-warning',
                            'absent'  => 'tr-badge-danger',
                            'izin', 'sakit' => 'tr-badge-info',
                            default   => 'tr-badge-gray',
                        };
                        $badgeLabel = match($st) {
                            'present' => 'Hadir Tepat Waktu',
                            'late'    => 'Terlambat '.($attendance?->late_minutes ?? 0).' Menit',
                            'absent'  => 'Alpa / Tidak Hadir',
                            'izin'    => 'Izin',
                            'sakit'   => 'Sakit',
                            default   => 'Belum Absen',
                        };
                        
                        // Menentukan icon badge berdasarkan status
                        $badgeIcon = match($st) {
                            'present' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>',
                            'late'    => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
                            'absent'  => '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>',
                            'izin', 'sakit' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>',
                            default   => '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>'
                        };
                    @endphp
                    
                    <div class="tr-badge-lg {{ $badgeCls }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">{!! $badgeIcon !!}</svg>
                        {{ $badgeLabel }}
                    </div>

                    <div class="tr-schedule-hint">
                        <span>Jadwal: <strong>{{ $workStartTime }} – {{ $workEndTime }}</strong></span>
                        <span class="tr-dot-divider">•</span>
                        <span>Toleransi: <strong>{{ $lateGraceMinutes }} Menit</strong></span>
                    </div>
                </div>
            </div>

            {{-- ─── CAMERA CARD ─── --}}
            <div class="tr-camera-card">
                <div class="tr-camera-header">
                    <h2 class="tr-camera-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        Autentikasi Wajah
                    </h2>
                    <p class="tr-camera-sub">Pastikan wajah terlihat jelas sebelum menekan tombol absen.</p>
                </div>
                
                <div class="tr-camera-body">
                    <form method="POST" action="{{ route('sdm.absensi.self_store') }}" enctype="multipart/form-data" id="absen-form">
                        @csrf
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input id="selfieDataInput" type="hidden" name="selfie_data" value="">
                        {{-- capture="user" = paksa kamera depan di mobile --}}
                        <input id="selfieCapture" type="file" name="selfie" accept="image/*" capture="user" style="display:none">

                        {{-- PREVIEW AREA --}}
                        <div class="tr-preview-box" id="previewBox">
                            <video id="selfieVideo" autoplay playsinline muted style="display:none;"></video>
                            <canvas id="selfieCanvas" style="display:none;"></canvas>
                            <img id="selfiePreview" src="" alt="Hasil Foto" style="display:none;">
                            
                            <div class="tr-preview-placeholder" id="previewPlaceholder">
                                <div class="tr-placeholder-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </div>
                                <p>Kamera belum aktif.<br>Ketuk <strong>Buka Kamera</strong> di bawah.</p>
                            </div>
                        </div>

                        {{-- CAMERA CONTROLS --}}
                        <div class="tr-camera-controls">
                            <button id="btnOpenCamera" type="button" class="tr-btn-control tr-btn-camera">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                                <span>Buka Kamera</span>
                            </button>
                            <button id="btnTakePhoto" type="button" class="tr-btn-control tr-btn-snap" disabled>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle></svg>
                                <span>Ambil Foto</span>
                            </button>
                        </div>
                        
                        <p class="tr-file-hint">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Diperlukan akses kamera langsung untuk validasi lokasi.
                        </p>

                        @if(($isWorking ?? true) === false)
                            <div class="tr-inline-warning">
                                <strong>Hari ini bukan hari kerja aktif.</strong>
                                Absen dibuka saat kalender kerja aktif.
                            </div>
                        @endif

                        {{-- ACTION BUTTONS (SUBMIT) --}}
                        <div class="tr-submit-actions">
                            @if($canCheckIn)
                                <button type="submit" name="action" value="in" class="tr-btn-action tr-btn-in">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                    Absen Masuk
                                </button>
                            @else
                                <button type="button" class="tr-btn-action is-disabled" disabled>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                    Absen Masuk
                                </button>
                            @endif

                            @if($canCheckOut)
                                @if(($opnameRequiredForCheckout ?? false) === true)
                                    <button type="button" class="tr-btn-action is-disabled" disabled>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        Absen Pulang
                                    </button>
                                @else
                                <button type="submit" name="action" value="out" class="tr-btn-action tr-btn-out">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                    Absen Pulang
                                </button>
                                @endif
                            @else
                                <button type="button" class="tr-btn-action is-disabled" disabled>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                    Absen Pulang
                                </button>
                            @endif
                        </div>

                        @if(($opnameRequiredForCheckout ?? false) === true)
                            <div class="tr-inline-warning">
                                <strong>Absen pulang terkunci.</strong> Selesaikan Opname Stok terlebih dahulu.
                                @can('create_opname_stok')
                                    <a class="tr-inline-link" href="{{ route('gudang.opname_sessions.create') }}">Buka Opname</a>
                                @endcan
                            </div>
                        @endif

                        @error('selfie')<div class="tr-error-msg">{{ $message }}</div>@enderror
                        @error('selfie_data')<div class="tr-error-msg">{{ $message }}</div>@enderror
                        @error('action')<div class="tr-error-msg">{{ $message }}</div>@enderror
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alertBox = document.querySelector('.tr-alert');
            if (alertBox) {
                try {
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } catch {
                    window.scrollTo(0, 0);
                }
            }
            const errorBox = document.querySelector('.tr-error-msg');
            if (!alertBox && errorBox) {
                try {
                    errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } catch {
                    window.scrollTo(0, document.body.scrollHeight);
                }
            }

            const captureInput = document.getElementById('selfieCapture');
            const selfieData   = document.getElementById('selfieDataInput');
            const imgPreview   = document.getElementById('selfiePreview');
            const video        = document.getElementById('selfieVideo');
            const canvas       = document.getElementById('selfieCanvas');
            const btnOpen      = document.getElementById('btnOpenCamera');
            const btnTake      = document.getElementById('btnTakePhoto');
            const placeholder  = document.getElementById('previewPlaceholder');

            if (!selfieData || !imgPreview || !video || !canvas || !btnOpen || !btnTake) return;

            let stream = null;

            // Cek apakah getUserMedia tersedia dan konteks aman
            const hasGetUserMedia = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);

            function stopStream() {
                if (!stream) return;
                stream.getTracks().forEach(t => t.stop());
                stream = null;
            }

            function showPreview(src) {
                imgPreview.src = src;
                imgPreview.style.display = 'block';
                video.style.display = 'none';
                if (placeholder) placeholder.style.display = 'none';
            }

            function hideCamera() {
                stopStream();
                video.style.display = 'none';
                video.srcObject = null;
                btnTake.disabled = true;
                btnOpen.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg> <span>Buka Kamera</span>';
            }

            // Fallback: gunakan input capture="user" (kamera saja, bukan galeri)
            function useCaptureInput() {
                if (!captureInput) return;
                captureInput.value = '';
                captureInput.click();
            }

            // Capture input change handler
            if (captureInput) {
                captureInput.addEventListener('change', function () {
                    const file = captureInput.files && captureInput.files[0];
                    if (!file) return;
                    selfieData.value = '';
                    showPreview(URL.createObjectURL(file));
                });
            }

            // Tombol Buka Kamera
            btnOpen.addEventListener('click', async function () {
                // Kalau sedang streaming, tutup
                if (stream) { hideCamera(); return; }

                if (!hasGetUserMedia) {
                    useCaptureInput();
                    return;
                }

                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                        audio: false
                    });
                    video.srcObject = stream;
                    video.style.display = 'block';
                    imgPreview.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'none';
                    btnTake.disabled = false;
                    btnOpen.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> <span>Tutup Kamera</span>';
                } catch (err) {
                    // getUserMedia gagal (permission denied, no camera, non-HTTPS)
                    useCaptureInput();
                }
            });

            // Tombol Ambil Foto
            btnTake.addEventListener('click', function () {
                if (!stream) return;
                const w = video.videoWidth || 640;
                const h = video.videoHeight || 480;
                canvas.width = w;
                canvas.height = h;
                
                // Flip camera if front facing (mirror effect)
                const ctx = canvas.getContext('2d');
                if (!ctx) return;
                ctx.translate(w, 0);
                ctx.scale(-1, 1);
                ctx.drawImage(video, 0, 0, w, h);
                
                const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                selfieData.value = dataUrl;

                // Isi file input jika DataTransfer tersedia
                canvas.toBlob(blob => {
                    if (!blob || !captureInput) return;
                    try {
                        const dt = new DataTransfer();
                        dt.items.add(new File([blob], 'selfie.jpg', { type: 'image/jpeg' }));
                        captureInput.files = dt.files;
                        if (captureInput.files && captureInput.files.length > 0) {
                            selfieData.value = '';
                        }
                    } catch {}
                }, 'image/jpeg', 0.9);

                showPreview(dataUrl);
                hideCamera();
            });

            // Prevent double submit on Action Buttons
            const absenForm = document.getElementById('absen-form');
            if(absenForm) {
                absenForm.addEventListener('submit', function(e) {
                    const hasFile = captureInput && captureInput.files && captureInput.files.length > 0;
                    const hasData = selfieData && selfieData.value && selfieData.value.length > 0;
                    if (!hasFile && !hasData) {
                        e.preventDefault();
                        const existing = this.querySelector('.tr-error-msg.tr-client');
                        if (existing) existing.remove();
                        const msg = document.createElement('div');
                        msg.className = 'tr-error-msg tr-client';
                        msg.textContent = 'Selfie wajib diunggah atau diambil dari kamera.';
                        this.appendChild(msg);
                        try {
                            msg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        } catch {}
                        return;
                    }
                    const actionBtns = this.querySelectorAll('.tr-btn-action');
                    actionBtns.forEach(btn => {
                        if(!btn.disabled) {
                            btn.style.opacity = '0.7';
                            btn.style.cursor = 'wait';
                        }
                    });
                });
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            
            --tr-primary: #3b82f6; 
            --tr-primary-hover: #2563eb;
            --tr-info: #0ea5e9;
            --tr-info-hover: #0284c7;
            
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;
            
            --tr-danger: #ef4444;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #991b1b;
            
            --tr-radius-xl: 20px;
            --tr-radius-lg: 16px;
            --tr-radius-md: 12px;
            --tr-shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --tr-shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            --tr-shadow-btn: 0 8px 16px -4px rgba(0, 0, 0, 0.15);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; }
        .tr-absen-container { max-width: 580px; margin: 0 auto; padding: 2rem 1.25rem 4rem; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: flex-start; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; line-height: 1.4; border: 1px solid transparent; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: #a7f3d0; }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: #fecaca; }
        .tr-alert-icon { flex-shrink: 0; }

        /* ── STATUS CARD (TOP) ── */
        .tr-status-card { background: var(--tr-surface); border-radius: var(--tr-radius-xl); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
        .tr-status-header { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        
        .tr-user-info { display: flex; align-items: center; gap: 10px; }
        .tr-avatar-placeholder { width: 36px; height: 36px; border-radius: 50%; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .tr-user-name { font-size: 1rem; font-weight: 800; color: var(--tr-text-main); letter-spacing: -0.01em; }
        
        .tr-date-badge { display: inline-flex; align-items: center; gap: 6px; padding: 0.35rem 0.75rem; background: var(--tr-bg); border: 1px solid var(--tr-border); border-radius: 999px; font-size: 0.75rem; font-weight: 700; color: var(--tr-text-muted); }

        .tr-status-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; background: #ffffff; }
        .tr-status-cell { padding: 1.25rem 1rem; text-align: center; border-right: 1px solid var(--tr-border-light); }
        .tr-status-cell:last-child { border-right: none; }
        .tr-highlight-cell { background: #f8fafc; }
        
        .tr-cell-label { font-size: 0.7rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .tr-cell-value { font-size: 1.2rem; font-weight: 900; color: var(--tr-text-main); }
        .tr-cell-value.tr-muted { color: #cbd5e1; }
        .tr-text-primary { color: var(--tr-primary); }

        .tr-status-footer { padding: 1.25rem 1.5rem; background: #ffffff; border-top: 1px dashed var(--tr-border-light); display: flex; flex-direction: column; align-items: center; gap: 0.75rem; }
        
        .tr-badge-lg { display: inline-flex; align-items: center; gap: 6px; padding: 0.4rem 1.25rem; border-radius: 999px; font-size: 0.85rem; font-weight: 800; letter-spacing: 0.02em; text-transform: uppercase; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-badge-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); }
        .tr-badge-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); }
        .tr-badge-info { background: #e0f2fe; color: #0369a1; }
        .tr-badge-gray { background: var(--tr-bg); color: var(--tr-text-muted); border: 1px solid var(--tr-border); }

        .tr-schedule-hint { font-size: 0.75rem; color: var(--tr-text-muted); display: flex; align-items: center; gap: 6px; flex-wrap: wrap; justify-content: center; }
        .tr-schedule-hint strong { color: var(--tr-text-main); font-weight: 700; }
        .tr-dot-divider { color: var(--tr-border); }

        /* ── CAMERA CARD (BOTTOM) ── */
        .tr-camera-card { background: var(--tr-surface); border-radius: var(--tr-radius-xl); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-md); overflow: hidden; }
        .tr-camera-header { padding: 1.5rem 1.5rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); text-align: center; }
        .tr-camera-title { font-size: 1.1rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.25rem 0; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .tr-camera-sub { font-size: 0.8rem; color: var(--tr-text-muted); margin: 0; line-height: 1.4; }
        
        .tr-camera-body { padding: 1.5rem; }

        /* Preview Box */
        .tr-preview-box { position: relative; width: 100%; aspect-ratio: 4/3; background: #f1f5f9; border: 2px dashed #cbd5e1; border-radius: 16px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; }
        .tr-preview-box video { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); /* Mirror effect for natural selfie */ }
        .tr-preview-box img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        
        .tr-preview-placeholder { text-align: center; color: var(--tr-text-light); display: flex; flex-direction: column; align-items: center; gap: 10px; }
        .tr-placeholder-icon { width: 56px; height: 56px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #94a3b8; }
        .tr-preview-placeholder p { font-size: 0.85rem; margin: 0; line-height: 1.5; }
        .tr-preview-placeholder strong { color: var(--tr-text-muted); }

        /* Camera Controls */
        .tr-camera-controls { display: flex; gap: 0.75rem; margin-bottom: 1rem; }
        .tr-btn-control { flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.85rem 1rem; border-radius: var(--tr-radius-md); font-size: 0.9rem; font-weight: 700; font-family: inherit; cursor: pointer; transition: all 0.2s; border: none; }
        .tr-btn-camera { background: var(--tr-bg); color: var(--tr-text-main); border: 1px solid var(--tr-border); }
        .tr-btn-camera:hover { background: var(--tr-border-light); }
        .tr-btn-snap { background: var(--tr-info); color: #ffffff; box-shadow: 0 4px 6px rgba(14, 165, 233, 0.2); }
        .tr-btn-snap:hover:not(:disabled) { background: var(--tr-info-hover); transform: translateY(-1px); }
        .tr-btn-control:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; transform: none; }

        .tr-file-hint { display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 0.75rem; color: var(--tr-text-light); margin: 0 0 1.5rem 0; }

        /* Action Buttons (Submit) */
        .tr-submit-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; border-top: 1px solid var(--tr-border-light); padding-top: 1.5rem; }
        .tr-btn-action { display: inline-flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; padding: 1rem; border-radius: var(--tr-radius-lg); font-size: 0.9rem; font-weight: 800; font-family: inherit; cursor: pointer; transition: all 0.2s; border: none; }
        
        .tr-btn-in { background: var(--tr-success); color: #ffffff; box-shadow: var(--tr-shadow-btn); }
        .tr-btn-in:hover { background: var(--tr-success-hover); transform: translateY(-2px); box-shadow: 0 10px 20px -4px rgba(16, 185, 129, 0.3); }
        
        .tr-btn-out { background: var(--tr-primary); color: #ffffff; box-shadow: var(--tr-shadow-btn); }
        .tr-btn-out:hover { background: var(--tr-primary-hover); transform: translateY(-2px); box-shadow: 0 10px 20px -4px rgba(59, 130, 246, 0.3); }
        
        .tr-btn-action.is-disabled { background: var(--tr-bg); color: var(--tr-text-light); border: 1px dashed var(--tr-border); box-shadow: none; cursor: not-allowed; transform: none; }

        .tr-error-msg { font-size: 0.8rem; font-weight: 600; color: var(--tr-danger); text-align: center; margin-top: 1rem; background: var(--tr-danger-light); padding: 0.5rem; border-radius: 6px; }

        .tr-inline-warning { margin-top: 0.9rem; font-size: 0.85rem; background: var(--tr-warning-bg); color: var(--tr-warning-text); border: 1px solid #fde68a; padding: 0.75rem 0.9rem; border-radius: 12px; text-align: center; font-weight: 700; }
        .tr-inline-link { margin-left: 0.35rem; color: inherit; text-decoration: underline; font-weight: 900; }

        /* ── RESPONSIVE ── */
        @media (max-width: 480px) {
            .tr-absen-container { padding: 1rem 1rem 3rem; }
            .tr-status-header { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .tr-date-badge { width: 100%; justify-content: center; }
            .tr-camera-controls { flex-direction: column; }
            .tr-submit-actions { grid-template-columns: 1fr; gap: 0.75rem; }
            .tr-btn-action { flex-direction: row; padding: 0.85rem; font-size: 1rem; }
        }
    </style>
    @endpush
</x-app-layout>
