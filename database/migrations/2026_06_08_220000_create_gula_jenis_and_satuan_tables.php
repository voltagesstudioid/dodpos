<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create gula_jenis table
        Schema::create('gula_jenis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // Create gula_satuan table
        Schema::create('gula_satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 30)->unique();
            $table->string('singkatan', 10)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // Add foreign key columns to gula_produk
        Schema::table('gula_produk', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_id')->nullable()->after('jenis');
            $table->unsignedBigInteger('satuan_id')->nullable()->after('satuan');
        });

        // Seed default jenis
        $jenisList = ['Galon', 'Dus', 'Botol', 'Gelas', 'Karung', 'Lainnya'];
        foreach ($jenisList as $j) {
            DB::table('gula_jenis')->insert([
                'nama' => $j, 'status' => 'aktif',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // Seed default satuan
        $satuanList = [
            ['nama' => 'Galon', 'singkatan' => 'gl'],
            ['nama' => 'Dus', 'singkatan' => 'ds'],
            ['nama' => 'Pcs', 'singkatan' => 'pcs'],
            ['nama' => 'Drum', 'singkatan' => 'drm'],
            ['nama' => 'Pail', 'singkatan' => 'pl'],
            ['nama' => 'Kg', 'singkatan' => 'kg'],
            ['nama' => 'Galon/Dus/Pcs', 'singkatan' => 'gl/ds/pcs'],
        ];
        foreach ($satuanList as $s) {
            DB::table('gula_satuan')->insert([
                'nama' => $s['nama'], 'singkatan' => $s['singkatan'],
                'status' => 'aktif', 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // Link existing gula_produk records to new tables
        $produks = DB::table('gula_produk')->get();
        foreach ($produks as $p) {
            if ($p->jenis) {
                $jenis = DB::table('gula_jenis')->where('nama', $p->jenis)->first();
                if ($jenis) {
                    DB::table('gula_produk')->where('id', $p->id)->update(['jenis_id' => $jenis->id]);
                }
            }
            if ($p->satuan) {
                $satuan = DB::table('gula_satuan')->where('nama', $p->satuan)->first();
                if ($satuan) {
                    DB::table('gula_produk')->where('id', $p->id)->update(['satuan_id' => $satuan->id]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('gula_produk', function (Blueprint $table) {
            $table->dropColumn(['jenis_id', 'satuan_id']);
        });
        Schema::dropIfExists('gula_satuan');
        Schema::dropIfExists('gula_jenis');
    }
};
