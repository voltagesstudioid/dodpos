<x-app-layout>
    <x-slot name="header">Log Aktivitas (Audit Trail)</x-slot>

    @php
    if (!function_exists('renderJsonNice')) {
        function renderJsonNice($data, $indent = 0) {
            if (!is_array($data)) {
                if (is_bool($data)) return $data ? 'True' : 'False';
                if ($data === null) return '<span style="color:#94a3b8;font-style:italic;">null</span>';
                return htmlspecialchars((string)$data);
            }
            if (empty($data)) return '<span style="color:#94a3b8;font-style:italic;">Kosong</span>';
            
            $html = '<div style="padding-left: ' . ($indent > 0 ? '12px' : '0') . '; border-left: ' . ($indent > 0 ? '2px solid #e2e8f0' : 'none') . '; margin-top: ' . ($indent > 0 ? '4px' : '0') . ';">';
            foreach($data as $k => $v) {
                $html .= '<div style="margin-bottom: 4px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 0.75rem;">';
                $html .= '<span style="color: #4338ca; font-weight: 600;">' . htmlspecialchars((string)$k) . '</span>: ';
                if (is_array($v)) {
                    $html .= '<div style="margin-top:2px;">' . renderJsonNice($v, $indent + 1) . '</div>';
                } else {
                    $valStr = is_bool($v) ? ($v ? 'True' : 'False') : ($v === null ? 'null' : (string)$v);
                    $color = is_numeric($v) ? '#059669' : (is_bool($v) ? '#d97706' : '#0f172a');
                    if ($v === null) $color = '#94a3b8';
                    $html .= '<span style="color: ' . $color . '; word-break: break-word;">' . htmlspecialchars($valStr) . '</span>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
            return $html;
        }
    }
    @endphp

    <style>
        .audit-panel { background: #fff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); overflow: hidden; border: 1px solid #e2e8f0; }
        .audit-header { padding: 1.5rem; background: linear-gradient(180deg, #fdfdfe 0%, #f8fafc 100%); border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        .audit-title-area { display: flex; align-items: center; gap: 1rem; }
        .audit-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
        .audit-title { font-weight: 800; color: #0f172a; font-size: 1.25rem; letter-spacing: -0.02em; }
        .audit-subtitle { color: #64748b; font-size: 0.85rem; margin-top: 2px; }
        
        .audit-filter { background: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end; }
        .audit-table-wrap { overflow-x: auto; }
        .audit-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        .audit-table th { background: #f8fafc; color: #475569; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 1rem 1.5rem; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .audit-table td { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .audit-table tbody tr:last-child td { border-bottom: none; }
        .audit-table tbody tr:hover td { background: #fdfefe; }
        
        .evt-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        .evt-created { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .evt-updated { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .evt-deleted { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .evt-performed { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        
        .data-diff-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem; margin-bottom: 0.5rem; font-family: ui-monospace, monospace; font-size: 0.75rem; }
        .data-diff-key { font-weight: 700; color: #0f172a; display: block; margin-bottom: 4px; }
        .data-diff-old { color: #dc2626; text-decoration: line-through; margin-right: 0.5rem; display: inline-block; }
        .data-diff-new { color: #16a34a; display: inline-block; }
        .data-block { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem; overflow-y: auto; max-height: 250px; }
        .data-block-title { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; color: #64748b; }

        /* Modal Styles */
        .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px); animation: fadeIn 0.2s ease; }
        .modal-content { background: #fff; border-radius: 16px; width: 100%; max-width: 450px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); transform: scale(0.95); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .modal-overlay.active { display: flex; }
        .modal-overlay.active .modal-content { transform: scale(1); opacity: 1; }
        .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .modal-title { font-weight: 800; font-size: 1.1rem; color: #0f172a; display: flex; align-items: center; gap: 0.5rem; }
        .modal-close { background: none; border: none; font-size: 1.5rem; color: #94a3b8; cursor: pointer; line-height: 1; transition: color 0.2s; }
        .modal-close:hover { color: #0f172a; }
        .modal-body { padding: 1.5rem; }
        .modal-footer { padding: 1.25rem 1.5rem; background: #f8fafc; border-top: 1px solid #f1f5f9; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; display: flex; justify-content: flex-end; gap: 0.75rem; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>

    <div class="page-container">
        @if(session('success'))
            <div class="alert alert-success animate-in">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger animate-in">❌ {{ session('error') }}</div>
        @endif

        <div class="audit-panel animate-in">
            <div class="audit-header">
                <div class="audit-title-area">
                    <div class="audit-icon">🕵️‍♂️</div>
                    <div>
                        <div class="audit-title">Log Aktivitas Sistem</div>
                        <div class="audit-subtitle">Lacak setiap perubahan (audit trail) untuk keamanan dan transparansi.</div>
                    </div>
                </div>
                @can('delete_log_aktivitas')
                    <button type="button" class="btn-danger" style="border-radius: 99px; padding: 0.6rem 1.25rem;" onclick="openDeleteModal()">
                        🗑️ Bersihkan Log
                    </button>
                @endcan
            </div>

            <div class="audit-filter">
                <form method="GET" action="{{ route('activity-log.index') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;width:100%;">
                    
                    <div style="flex:1; min-width: 150px;">
                        <label class="form-label" style="font-size:0.7rem; color:#64748b; margin-bottom:4px; display:block;">Pelaku (User)</label>
                        <select name="user_id" class="form-input" style="padding: 0.5rem 0.75rem;">
                            <option value="">Semua Pengguna</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex:1; min-width: 140px;">
                        <label class="form-label" style="font-size:0.7rem; color:#64748b; margin-bottom:4px; display:block;">Jenis Aksi</label>
                        <select name="event" class="form-input" style="padding: 0.5rem 0.75rem;">
                            <option value="">Semua Aksi</option>
                            <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created (Tambah)</option>
                            <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated (Ubah)</option>
                            <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted (Hapus)</option>
                            <option value="performed" {{ request('event') == 'performed' ? 'selected' : '' }}>Performed (Sistem)</option>
                        </select>
                    </div>

                    <div style="flex:2; min-width: 250px; display:flex; gap:0.5rem; align-items:flex-end;">
                        <div style="flex:1;">
                            <label class="form-label" style="font-size:0.7rem; color:#64748b; margin-bottom:4px; display:block;">Mulai Tgl</label>
                            <input type="date" name="date_start" value="{{ request('date_start') }}" class="form-input" style="padding: 0.5rem 0.75rem;">
                        </div>
                        <div style="padding-bottom:0.5rem; color:#94a3b8;">-</div>
                        <div style="flex:1;">
                            <label class="form-label" style="font-size:0.7rem; color:#64748b; margin-bottom:4px; display:block;">Sampai Tgl</label>
                            <input type="date" name="date_end" value="{{ request('date_end') }}" class="form-input" style="padding: 0.5rem 0.75rem;">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn-primary" style="padding: 0.5rem 1.25rem; height: 38px;">Cari</button>
                    </div>
                    @if(request('user_id') || request('event') || request('date_start') || request('date_end'))
                        <div>
                            <a href="{{ route('activity-log.index') }}" class="btn-secondary" style="padding: 0.5rem 1.25rem; height: 38px;">Reset</a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="audit-table-wrap">
                <table class="audit-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Waktu</th>
                            <th style="width: 20%;">Pelaku (User)</th>
                            <th style="width: 15%;">Objek</th>
                            <th style="width: 50%;">Rincian (Data Lama ➔ Data Baru)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem;">{{ $log->created_at->format('d M Y') }}</div>
                                <div style="color: #64748b; font-size: 0.75rem;">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td>
                                @if($log->causer)
                                    <div style="display:flex; align-items:center; gap:0.6rem;">
                                        <div style="width:28px;height:28px;border-radius:6px;background:linear-gradient(135deg,#eef2ff,#c7d2fe);color:#4338ca;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.7rem;">
                                            {{ strtoupper(substr($log->causer->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:700;color:#0f172a;font-size:0.85rem;line-height:1.2;">{{ $log->causer->name }}</div>
                                            <div style="font-size:0.7rem;color:#64748b;">{{ $log->causer->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div style="display:flex; align-items:center; gap:0.6rem;">
                                        <div style="width:28px;height:28px;border-radius:6px;background:#f1f5f9;color:#64748b;display:flex;align-items:center;justify-content:center;font-size:0.9rem;">🤖</div>
                                        <div style="font-weight:600;color:#475569;font-size:0.85rem;font-style:italic;">Sistem (Auto)</div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $evtClass = 'evt-performed';
                                    if($log->event === 'created') $evtClass = 'evt-created';
                                    if($log->event === 'updated') $evtClass = 'evt-updated';
                                    if($log->event === 'deleted') $evtClass = 'evt-deleted';
                                @endphp
                                <div style="margin-bottom:0.5rem;"><span class="evt-badge {{ $evtClass }}">{{ ucfirst($log->event) }}</span></div>
                                <div style="font-size:0.75rem; color:#475569; font-weight:600; background:#f8fafc; padding:3px 6px; border-radius:4px; border:1px solid #e2e8f0; display:inline-block; word-break: break-all;">
                                    {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                </div>
                                <div style="font-size:0.7rem; color:#94a3b8; margin-top:0.3rem;">{{ $log->description }}</div>
                            </td>
                            <td>
                                @if($log->properties && count($log->properties) > 0)
                                    @php
                                        $old = $log->properties['old'] ?? null;
                                        $new = $log->properties['attributes'] ?? null;
                                    @endphp
                                    
                                    @if($log->event === 'updated' && is_array($old) && is_array($new))
                                        <div style="display:flex;flex-direction:column;gap:4px;">
                                            @foreach($new as $key => $newValue)
                                                @if(array_key_exists($key, $old) && $old[$key] !== $newValue)
                                                    <div class="data-diff-box">
                                                        <span class="data-diff-key">{{ $key }}</span>
                                                        <div style="display:flex; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                                                            @if(is_array($old[$key]) || is_object($old[$key]))
                                                                <span class="data-diff-old" style="text-decoration:none; opacity:0.7;">(Complex Data)</span>
                                                            @else
                                                                <span class="data-diff-old">{{ $old[$key] === null ? 'null' : $old[$key] }}</span>
                                                            @endif
                                                            
                                                            <span style="color:#94a3b8; font-size:1rem;">➔</span>
                                                            
                                                            @if(is_array($newValue) || is_object($newValue))
                                                                <span class="data-diff-new" style="opacity:0.8;">(Complex Data)</span>
                                                            @else
                                                                <span class="data-diff-new">{{ $newValue === null ? 'null' : $newValue }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @elseif($log->event === 'created' && is_array($new))
                                        <div class="data-block" style="border-color:#bbf7d0; background:#f0fdf4;">
                                            <div class="data-block-title" style="color:#166534;">Data Baru Disimpan</div>
                                            {!! renderJsonNice($new) !!}
                                        </div>
                                    @elseif($log->event === 'deleted' && is_array($old))
                                        <div class="data-block" style="border-color:#fecaca; background:#fef2f2;">
                                            <div class="data-block-title" style="color:#991b1b;">Data Dihapus</div>
                                            {!! renderJsonNice($old) !!}
                                        </div>
                                    @else
                                        {{-- Fallback untuk custom properties (bukan bawaan model attributes/old) --}}
                                        <div class="data-block">
                                            <div class="data-block-title">Rincian Data</div>
                                            {!! renderJsonNice($log->properties->toArray()) !!}
                                        </div>
                                    @endif
                                @else
                                    <div style="font-size:0.8rem; color:#94a3b8; font-style:italic; padding:0.5rem 0;">Tidak ada properti tercatat.</div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <div class="empty-state-icon">📭</div>
                                <div class="empty-state-title">Belum Ada Log Aktivitas</div>
                                <div class="empty-state-desc">Pilih filter yang lebih luas atau lakukan aktivitas di dalam sistem.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div style="padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; background: #fafbff;">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    @can('delete_log_aktivitas')
    <!-- Modal Hapus Log -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><span style="color:#ef4444;">⚠️</span> Bersihkan Riwayat Log</div>
                <button type="button" class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <form method="POST" action="{{ route('activity-log.prune') }}">
                @csrf
                <div class="modal-body">
                    <p style="font-size:0.85rem; color:#475569; margin-bottom:1.25rem; line-height:1.5;">
                        Pilih rentang tanggal log aktivitas yang ingin dihapus. Data yang dihapus tidak dapat dikembalikan.
                    </p>
                    
                    <div class="form-group">
                        <label class="form-label">Tipe Pelaku (Opsional)</label>
                        <select name="user_id" class="form-input">
                            <option value="">Semua Pengguna</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display:flex; gap:1rem; margin-top:1rem;">
                        <div class="form-group" style="flex:1;">
                            <label class="form-label">Mulai Tgl <span class="text-muted">(Opsional)</span></label>
                            <input type="date" name="date_start" class="form-input">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label class="form-label">Sampai Tgl <span class="required">*</span></label>
                            <input type="date" name="date_end" class="form-input" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn-danger">Ya, Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.add('active');
            // Isi otomatis tanggal jika ada di filter atas
            const filterEnd = document.querySelector('input[name="date_end"]').value;
            if(filterEnd) {
                document.querySelector('#deleteModal input[name="date_end"]').value = filterEnd;
            }
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        // Tutup modal jika klik di luar box
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if(e.target === this) closeDeleteModal();
        });
    </script>
    @endcan

</x-app-layout>
