@extends('layouts.app')

@section('title', 'Tambah Master Rute Kanvas')

@section('content')
<div class="content-body">
    <div class="header-section">
        <div class="breadcrumb">
            <a href="{{ route('kanvas.route.index') }}">Rute</a> / Tambah Rute
        </div>
        <h1 class="page-title">Buat Journey Plan Baru</h1>
    </div>

    <form action="{{ route('kanvas.route.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white"><h5 class="mb-0">Info Rute / Area</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Rute</label>
                            <input type="text" name="name" class="form-control" placeholder="Cth: Rute Senenan Blok S" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jadwal Kunjungan Mingguan (Hari)</label>
                            <select name="day_of_week" class="form-select" required>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan / Patokan</label>
                            <textarea name="area_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pilih Toko dalam Rute</h5>
                    </div>
                    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Nama Toko / Pelanggan</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $c)
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" name="customer_ids[]" value="{{ $c->id }}" id="cust_{{ $c->id }}">
                                    </td>
                                    <td><label for="cust_{{ $c->id }}" style="cursor: pointer;"><strong>{{ $c->name }}</strong></label></td>
                                    <td><small>{{ $c->address ?? '-' }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Journey Plan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
