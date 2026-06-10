<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minyak_jenis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->integer('urutan')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('minyak_satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 30)->unique();
            $table->string('singkatan', 10)->nullable();
            $table->integer('urutan')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // Seed default data
        DB::table('minyak_jenis')->insert([
            ['nama' => 'Minyak Goreng Curah', 'urutan' => 1, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Minyak Sawit', 'urutan' => 2, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Minyak Kelapa', 'urutan' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('minyak_satuan')->insert([
            ['nama' => 'Liter', 'singkatan' => 'L', 'urutan' => 1, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Galon', 'singkatan' => 'Gln', 'urutan' => 2, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Drum', 'singkatan' => 'Drm', 'urutan' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pail', 'singkatan' => 'Pl', 'urutan' => 4, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Jerigen', 'singkatan' => 'Jrg', 'urutan' => 5, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kg', 'singkatan' => 'Kg', 'urutan' => 6, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('minyak_satuan');
        Schema::dropIfExists('minyak_jenis');
    }
};
