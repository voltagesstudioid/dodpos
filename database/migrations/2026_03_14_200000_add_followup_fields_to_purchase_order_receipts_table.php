<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_order_receipts', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_order_receipts', 'needs_followup')) {
                $table->boolean('needs_followup')->default(false)->after('status');
            }
            if (! Schema::hasColumn('purchase_order_receipts', 'followup_status')) {
                $table->enum('followup_status', ['open', 'resolved'])->nullable()->after('needs_followup');
            }
            if (! Schema::hasColumn('purchase_order_receipts', 'followup_action')) {
                $table->string('followup_action', 60)->nullable()->after('followup_status');
            }
            if (! Schema::hasColumn('purchase_order_receipts', 'followup_notes')) {
                $table->text('followup_notes')->nullable()->after('followup_action');
            }
            if (! Schema::hasColumn('purchase_order_receipts', 'resolved_by')) {
                $table->foreignId('resolved_by')->nullable()->after('followup_notes')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('purchase_order_receipts', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('resolved_by');
            }
        });

        Schema::table('purchase_order_receipts', function (Blueprint $table) {
            if (! Schema::hasIndex('purchase_order_receipts', 'por_followup_idx')) {
                $table->index(['needs_followup', 'followup_status', 'created_at'], 'por_followup_idx');
            }
        });

        if (Schema::hasTable('purchase_order_receipts')
            && Schema::hasColumn('purchase_order_receipts', 'status')
            && Schema::hasColumn('purchase_order_receipts', 'needs_followup')
            && Schema::hasColumn('purchase_order_receipts', 'followup_status')
        ) {
            DB::table('purchase_order_receipts')
                ->where('status', 'partial')
                ->where(function ($q) {
                    $q->whereNull('followup_status')->orWhere('followup_status', '');
                })
                ->update([
                    'needs_followup' => 1,
                    'followup_status' => 'open',
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('purchase_order_receipts', function (Blueprint $table) {
            if (Schema::hasIndex('purchase_order_receipts', 'por_followup_idx')) {
                $table->dropIndex('por_followup_idx');
            }
        });

        Schema::table('purchase_order_receipts', function (Blueprint $table) {
            $table->dropColumn([
                'needs_followup',
                'followup_status',
                'followup_action',
                'followup_notes',
                'resolved_by',
                'resolved_at',
            ]);
        });
    }
};
