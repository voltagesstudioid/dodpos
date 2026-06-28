<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mineral_produk', function (Blueprint $table) {
            $table->foreignId('satuan_id')->nullable()->after('satuan')
                ->constrained('mineral_satuan')->nullOnDelete();
        });

        // Migrate existing data: match satuan string to mineral_satuan.nama
        DB::statement('UPDATE mineral_produk p INNER JOIN mineral_satuan s ON p.satuan = s.nama SET p.satuan_id = s.id');

        // For any unmatched records, create fallback satuan entries
        $orphans = DB::table('mineral_produk')->whereNull('satuan_id')->whereNotNull('satuan')->get();
        foreach ($orphans as $p) {
            $existing = DB::table('mineral_satuan')->where('nama', $p->satuan)->first();
            if (!$existing) {
                $id = DB::table('mineral_satuan')->insertGetId([
                    'nama' => $p->satuan,
                    'singkatan' => null,
                    'urutan' => 999,
                    'status' => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('mineral_produk')->where('id', $p->id)->update(['satuan_id' => $id]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('mineral_produk', function (Blueprint $table) {
            $table->dropForeign(['satuan_id']);
            $table->dropColumn('satuan_id');
        });
    }
};
