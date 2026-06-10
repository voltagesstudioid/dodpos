<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasgar_loadings', function (Blueprint $table) {
            // Source type: toko or gudang
            $table->enum('sumber', ['toko', 'gudang'])->default('gudang')->after('sales_id');

            // Change status enum to support new workflow
            $table->dropColumn('status');
        });

        // Re-add status with new enum values (must be done in separate step)
        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->enum('status', [
                'pending',       // Sales submitted request
                'preparing',     // Admin is preparing goods
                'ready',         // Admin confirmed goods ready for pickup
                'picked_up',     // Sales picked up & cross-checked
                'completed',     // All sold, setoran done
                'rejected',      // Request rejected by admin
            ])->default('pending')->after('tanggal');
        });

        // Add workflow tracking columns
        Schema::table('pasgar_loadings', function (Blueprint $table) {
            // Admin prepares
            $table->foreignId('prepared_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            $table->timestamp('prepared_at')->nullable()->after('prepared_by');

            // Admin confirms ready
            $table->foreignId('confirmed_by')->nullable()->after('prepared_at')->constrained('users')->nullOnDelete();
            $table->timestamp('ready_at')->nullable()->after('confirmed_by');

            // Sales picks up & cross-checks
            $table->foreignId('picked_up_by')->nullable()->after('ready_at')->constrained('users')->nullOnDelete();
            $table->timestamp('picked_up_at')->nullable()->after('picked_up_by');
            $table->text('cross_check_notes')->nullable()->after('picked_up_at');
        });

        // Add qty_dikirim to loading items (admin prepares = qty actually prepared)
        Schema::table('pasgar_loading_items', function (Blueprint $table) {
            $table->integer('qty_dikirim')->default(0)->after('qty_disetujui');
        });
    }

    public function down(): void
    {
        Schema::table('pasgar_loading_items', function (Blueprint $table) {
            $table->dropColumn('qty_dikirim');
        });

        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->dropForeign(['prepared_by']);
            $table->dropForeign(['confirmed_by']);
            $table->dropForeign(['picked_up_by']);
            $table->dropColumn([
                'sumber',
                'prepared_by', 'prepared_at',
                'confirmed_by', 'ready_at',
                'picked_up_by', 'picked_up_at',
                'cross_check_notes',
            ]);
        });

        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('pasgar_loadings', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
        });
    }
};
