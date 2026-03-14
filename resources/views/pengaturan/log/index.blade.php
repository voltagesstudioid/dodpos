<x-app-layout>
    <x-slot name="header">Log Aktivitas (Audit Trail)</x-slot>

    <div class="page-container">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">🕵️‍♂️ Log Aktivitas Sistem</div>
                    <div class="panel-subtitle">Memantau riwayat perubahan data penting oleh pengguna.</div>
                </div>
                @can('delete_log_aktivitas')
                    <form method="POST" action="{{ route('activity-log.prune') }}" style="display:flex;gap:0.5rem;align-items:center;">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <input type="hidden" name="event" value="{{ request('event') }}">
                        <input type="hidden" name="date_start" value="{{ request('date_start') }}">
                        <input type="hidden" name="date_end" value="{{ request('date_end') }}">
                        <button type="submit" class="btn-danger btn-sm" onclick="return confirm('Hapus log sampai tanggal akhir filter? Aksi ini tidak bisa dibatalkan.')"
                            {{ request('date_end') ? '' : 'disabled' }}>
                            🗑️ Hapus (butuh Tgl Akhir)
                        </button>
                    </form>
                @endcan
            </div>

            <div class="filter-bar">
                <form method="GET" action="{{ route('activity-log.index') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
                    
                    <select name="user_id" class="form-input" style="max-width:200px;">
                        <option value="">Semua Pengguna</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>

                    <select name="event" class="form-input" style="max-width:160px;">
                        <option value="">Semua Aksi</option>
                        <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created (Tambah)</option>
                        <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated (Ubah)</option>
                        <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted (Hapus)</option>
                        <option value="performed" {{ request('event') == 'performed' ? 'selected' : '' }}>Performed (Aksi)</option>
                    </select>

                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <input type="date" name="date_start" value="{{ request('date_start') }}" class="form-input" style="max-width:140px;" title="Tanggal Mulai">
                        <span style="color:#64748b;">-</span>
                        <input type="date" name="date_end" value="{{ request('date_end') }}" class="form-input" style="max-width:140px;" title="Tanggal Akhir">
                    </div>

                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    @if(request('user_id') || request('event') || request('date_start') || request('date_end'))
                        <a href="{{ route('activity-log.index') }}" class="btn-secondary btn-sm">Reset</a>
                    @endif
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 140px;">Waktu</th>
                            <th style="width: 180px;">Pengguna (Pelaku)</th>
                            <th style="width: 120px;">Aksi</th>
                            <th style="width: 200px;">Objek Terkait</th>
                            <th>Rincian Perubahan (Data Lama ➔ Baru)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td style="font-size:0.8125rem;color:#64748b;white-space:nowrap;">
                                {{ $log->created_at->format('d M Y, H:i') }}
                            </td>
                            <td>
                                @if($log->causer)
                                    <div style="font-weight:600;color:#1e293b;font-size:0.875rem;">{{ $log->causer->name }}</div>
                                    <div style="font-size:0.75rem;color:#64748b;">{{ $log->causer->email }}</div>
                                @else
                                    <span class="text-muted" style="font-style:italic;">Sistem / Anonymous</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $evtColor = match($log->event) {
                                        'created' => 'badge-success',
                                        'updated' => 'badge-warning',
                                        'deleted' => 'badge-danger',
                                        default => 'badge-gray'
                                    };
                                @endphp
                                <span class="badge {{ $evtColor }}">{{ ucfirst($log->event) }}</span>
                            </td>
                            <td>
                                <div style="font-size:0.8125rem;font-family:monospace;color:#4338ca;background:#e0e7ff;padding:2px 6px;border-radius:4px;display:inline-block;word-break:break-all;">
                                    {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                </div>
                                <div style="font-size:0.75rem;color:#64748b;margin-top:4px;">{{ $log->description }}</div>
                            </td>
                            <td style="font-size:0.8125rem;">
                                @if($log->properties && count($log->properties) > 0)
                                    @php
                                        $old = $log->properties['old'] ?? [];
                                        $new = $log->properties['attributes'] ?? [];
                                    @endphp
                                    
                                    @if($log->event == 'updated' && !empty($old) && !empty($new))
                                        <div style="display:flex;flex-direction:column;gap:4px;">
                                            @foreach($new as $key => $newValue)
                                                @if(array_key_exists($key, $old) && $old[$key] != $newValue)
                                                    <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:6px 8px;border-radius:6px;">
                                                        <strong style="color:#0f172a;">{{ $key }}:</strong><br>
                                                        <span style="color:#dc2626;text-decoration:line-through;margin-right:6px;">{{ is_array($old[$key]) ? json_encode($old[$key]) : ($old[$key] ?? 'null') }}</span>
                                                        <span style="color:#16a34a;">➔ {{ is_array($newValue) ? json_encode($newValue) : ($newValue ?? 'null') }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @elseif($log->event == 'created' && !empty($new))
                                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:6px 8px;border-radius:6px;max-height:100px;overflow-y:auto;">
                                            <span style="color:#16a34a;">Data Baru:</span>
                                            <pre style="margin:2px 0 0;font-size:0.7rem;color:#166534;white-space:pre-wrap;">{{ json_encode($new, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @elseif($log->event == 'deleted' && !empty($old))
                                        <div style="background:#fef2f2;border:1px solid #fecaca;padding:6px 8px;border-radius:6px;max-height:100px;overflow-y:auto;">
                                            <span style="color:#dc2626;">Data Dihapus:</span>
                                            <pre style="margin:2px 0 0;font-size:0.7rem;color:#991b1b;white-space:pre-wrap;">{{ json_encode($old, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @elseif(!empty($log->properties))
                                        <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:6px 8px;border-radius:6px;max-height:160px;overflow-y:auto;">
                                            <pre style="margin:2px 0 0;font-size:0.7rem;color:#0f172a;white-space:pre-wrap;">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @else
                                        <span class="text-muted" style="font-style:italic;">Detail tidak tersedia</span>
                                    @endif
                                @else
                                    <span class="text-muted" style="font-style:italic;">Tidak ada properti tercatat</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-state-icon">🕵️‍♂️</div>
                                <div class="empty-state-title">Belum ada log aktivitas</div>
                                <div class="empty-state-desc">Riwayat perubahan data akan muncul di sini.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <nav aria-label="pagination" style="margin-top: 1rem;">
                    {{ $logs->links() }}
                </nav>
            @endif
        </div>

        {{-- INFO DEVELOPER UNTUK PEMBELAJARAN MVC --}}
        <div style="margin-top: 1.5rem; padding: 1.25rem; background: #eef2ff; border: 1px dashed #6366f1; border-radius: 12px; font-size: 0.8125rem; color: #4338ca; display: flex; flex-direction: column; gap: 0.5rem; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.1);">
            <div style="font-weight: 700; font-size: 0.875rem; display: flex; align-items: center; gap: 0.375rem;">
                <span>🛠️</span> Info Developer (Struktur MVC Halaman Ini)
            </div>
            <div>
                🖥️ <b>Web (Tampilan/View):</b> 
                <code style="background: rgba(255,255,255,0.7); padding: 0.125rem 0.375rem; border-radius: 4px; font-size: 0.75rem;">resources/views/pengaturan/log/index.blade.php</code>
            </div>
            <div>
                ⚙️ <b>App (Logika/Controller):</b> 
                <code style="background: rgba(255,255,255,0.7); padding: 0.125rem 0.375rem; border-radius: 4px; font-size: 0.75rem;">app/Http/Controllers/ActivityLogController.php</code> (Fungsi <code>index</code>)
            </div>
        </div>
    </div>
</x-app-layout>
