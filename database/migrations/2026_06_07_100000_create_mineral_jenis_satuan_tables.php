<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mineral_jenis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->integer('urutan')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('mineral_satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 30)->unique();
            $table->string('singkatan', 10)->nullable();
            $table->integer('urutan')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // Seed default data
        DB::table('mineral_jenis')->insert([
            ['nama' => 'Galon', 'urutan' => 1, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dus', 'urutan' => 2, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Botol', 'urutan' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Gelas', 'urutan' => 4, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Lainnya', 'urutan' => 5, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('mineral_satuan')->insert([
            ['nama' => 'Pcs', 'singkatan' => 'Pcs', 'urutan' => 1, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Botol', 'singkatan' => 'Btl', 'urutan' => 2, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Galon', 'singkatan' => 'Gln', 'urutan' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dus', 'singkatan' => 'Dus', 'urutan' => 4, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Drum', 'singkatan' => 'Drm', 'urutan' => 5, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pail', 'singkatan' => 'Pl', 'urutan' => 6, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kg', 'singkatan' => 'Kg', 'urutan' => 7, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Lusin', 'singkatan' => 'Lsn', 'urutan' => 8, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('mineral_satuan');
        Schema::dropIfExists('mineral_jenis');
    }
};
