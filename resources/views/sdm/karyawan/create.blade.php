<x-app-layout>
    <x-slot name="header">SDM / HR</x-slot>

    <div class="page-container">
        <div style="max-width: 800px; margin: 0 auto;">
            {{-- Header Section --}}
            <div class="page-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 class="page-header-title" style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0;">
                        <span style="background: #e0f2fe; padding: 0.5rem; border-radius: 10px; margin-right: 0.5rem;">👤</span>
                        Tambah Karyawan Baru
                    </h1>
                    <p class="page-header-subtitle" style="color: #64748b; margin-top: 0.5rem; font-size: 0.9rem;">
                        Lengkapi biodata karyawan. Akun sistem dapat dibuatkan setelah data tersimpan.
                    </p>
                </div>
                <a href="{{ route('sdm.karyawan.index') }}" class="btn-secondary" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; border-radius: 10px; font-weight: 600;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            {{-- Error Alert --}}
            @if($errors->any())
                <div class="alert alert-danger" role="alert" style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem;">
                    <div style="font-weight: 800; color: #991b1b; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Terdapat kesalahan input:
                    </div>
                    <ul style="margin: 0; padding-left: 1.5rem; color: #b91c1c; font-size: 0.85rem;">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="panel" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); overflow: hidden;">
                <div class="panel-header" style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafafa;">
                    <h3 class="panel-title" style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b;">📋 Informasi Personal & Pekerjaan</h3>
                </div>

                <div class="panel-body" style="padding: 1.5rem;">
                    <form method="POST" action="{{ route('sdm.karyawan.store') }}">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label" style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: #334155; font-size: 0.9rem;">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                class="form-input @error('name') input-error @enderror" 
                                style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #cbd5e1; outline: none; transition: 0.2s;"
                                onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)';"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';"
                                required autofocus placeholder="Masukkan nama lengkap karyawan">
                            @error('name') <div class="form-error" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.4rem; font-weight: 600;">{{ $message }}</div> @enderror
                        </div>

                        {{-- Grid Row 1: HP & Jabatan --}}
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label class="form-label" style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: #334155; font-size: 0.9rem;">No. Handphone / WA</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" 
                                    class="form-input @error('phone') input-error @enderror" 
                                    style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #cbd5e1;"
                                    placeholder="Contoh: 08123456789">
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: #334155; font-size: 0.9rem;">Jabatan</label>
                                <input type="text" name="position" value="{{ old('position') }}" 
                                    class="form-input @error('position') input-error @enderror" 
                                    style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #cbd5e1;"
                                    placeholder="Contoh: Kasir, Admin Gudang, dll">
                            </div>
                        </div>

                        {{-- Grid Row 2: Gaji & Uang Makan --}}
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid #f1f5f9;">
                            <div class="form-group">
                                <label class="form-label" style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: #334155; font-size: 0.9rem;">Gaji Pokok (Bulanan)</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #64748b; font-weight: 600;">Rp</span>
                                    <input type="number" name="basic_salary" value="{{ old('basic_salary', 0) }}" 
                                        class="form-input" style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.8rem; border-radius: 10px; border: 1px solid #cbd5e1;"
                                        min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: #334155; font-size: 0.9rem;">Uang Kehadiran (Per Hari)</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #64748b; font-weight: 600;">Rp</span>
                                    <input type="number" name="daily_allowance" value="{{ old('daily_allowance', 0) }}" 
                                        class="form-input" style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.8rem; border-radius: 10px; border: 1px solid #cbd5e1;"
                                        min="0">
                                </div>
                            </div>
                        </div>

                        {{-- Grid Row 3: Tgl Masuk & Status --}}
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label class="form-label" style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: #334155; font-size: 0.9rem;">Tanggal Masuk</label>
                                <input type="date" name="join_date" value="{{ old('join_date') }}" 
                                    class="form-input" style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #cbd5e1;">
                            </div>
                            <div class="form-group" style="display: flex; align-items: flex-end;">
                                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; background: #f1f5f9; padding: 0.75rem 1rem; border-radius: 10px; width: 100%; border: 1px solid #e2e8f0; transition: 0.2s;" 
                                    onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f1f5f9';">
                                    <input type="checkbox" name="active" value="1" {{ old('active','1')=='1' ? 'checked':'' }} 
                                        style="width: 20px; height: 20px; accent-color: #6366f1; cursor: pointer;">
                                    <span style="font-weight: 700; color: #334155; font-size: 0.9rem;">Karyawan Aktif</span>
                                </label>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="form-group" style="margin-bottom: 2rem;">
                            <label class="form-label" style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: #334155; font-size: 0.9rem;">Catatan Tambahan</label>
                            <textarea name="notes" rows="3" class="form-input" 
                                style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #cbd5e1; resize: none;" 
                                placeholder="Keterangan tambahan jika ada...">{{ old('notes') }}</textarea>
                        </div>

                        {{-- Form Actions --}}
                        <div style="display: flex; gap: 1rem; justify-content: flex-end; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                            <a href="{{ route('sdm.karyawan.index') }}" style="color: #64748b; font-weight: 700; text-decoration: none; font-size: 0.9rem; padding: 0.75rem 1.5rem;">Batal</a>
                            <button type="submit" class="btn-primary" style="background: #0f172a; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2);"
                                onmouseover="this.style.background='#1e293b'; this.style.transform='translateY(-2px)';" 
                                onmouseout="this.style.background='#0f172a'; this.style.transform='translateY(0)';">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>