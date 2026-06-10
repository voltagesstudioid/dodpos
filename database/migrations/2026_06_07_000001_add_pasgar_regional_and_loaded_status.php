<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create pasgar_regional table
        Schema::create('pasgar_regional', function (Blueprint $table) {
            $table->id();
            $table->string('kode_regional', 20)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Add regional_id to pasgar_sales
        Schema::table('pasgar_sales', function (Blueprint $table) {
            $table->foreignId('regional_id')->nullable()->after('user_id')->constrained('pasgar_regional')->nullOnDelete();
        });

        // 3. Add 'loaded' status to pasgar_loadings (must drop & re-add enum)
        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->enum('status', [
                'pending',       // Sales submitted request
                'preparing',     // Admin is preparing goods
                'ready',         // Admin confirmed goods ready for pickup
                'picked_up',     // Sales picked up & cross-checked
                'loaded',        // Sales loaded goods into vehicle
                'completed',     // All sold, setoran done
                'rejected',      // Request rejected by admin
            ])->default('pending')->after('tanggal');
        });

        // 4. Add loaded_by / loaded_at columns
        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->foreignId('loaded_by')->nullable()->after('cross_check_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('loaded_at')->nullable()->after('loaded_by');
        });
    }

    public function down(): void
    {
        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->dropForeign(['loaded_by']);
            $table->dropColumn(['loaded_by', 'loaded_at']);
        });

        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 'preparing', 'ready', 'picked_up', 'completed', 'rejected',
            ])->default('pending')->after('tanggal');
        });

        Schema::table('pasgar_sales', function (Blueprint $table) {
            $table->dropForeign(['regional_id']);
            $table->dropColumn('regional_id');
        });

        Schema::dropIfExists('pasgar_regional');
    }
};
