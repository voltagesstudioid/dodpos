<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('product_requests', 'from_warehouse_id')) {
                $table->foreignId('from_warehouse_id')->nullable()->after('product_id')->constrained('warehouses')->nullOnDelete();
            }
            if (! Schema::hasColumn('product_requests', 'to_warehouse_id')) {
                $table->foreignId('to_warehouse_id')->nullable()->after('from_warehouse_id')->constrained('warehouses')->nullOnDelete();
            }
            if (! Schema::hasColumn('product_requests', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('product_requests', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            if (! Schema::hasColumn('product_requests', 'transfer_reference')) {
                $table->string('transfer_reference', 120)->nullable()->after('supervisor_notes');
            }
        });

        Schema::table('product_requests', function (Blueprint $table) {
            if (! Schema::hasIndex('product_requests', 'product_requests_type_status_idx')) {
                $table->index(['type', 'status'], 'product_requests_type_status_idx');
            }
            if (! Schema::hasIndex('product_requests', 'product_requests_transfer_ref_idx')) {
                $table->index(['transfer_reference'], 'product_requests_transfer_ref_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_requests', function (Blueprint $table) {
            if (Schema::hasIndex('product_requests', 'product_requests_type_status_idx')) {
                $table->dropIndex('product_requests_type_status_idx');
            }
            if (Schema::hasIndex('product_requests', 'product_requests_transfer_ref_idx')) {
                $table->dropIndex('product_requests_transfer_ref_idx');
            }
        });

        Schema::table('product_requests', function (Blueprint $table) {
            $table->dropColumn([
                'from_warehouse_id',
                'to_warehouse_id',
                'approved_by',
                'approved_at',
                'transfer_reference',
            ]);
        });
    }
};

