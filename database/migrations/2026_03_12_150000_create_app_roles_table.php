<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_roles', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        $defaults = [
            'supervisor' => 'Supervisor',
            'admin_sales' => 'Admin Sales',
            'admin1' => 'Admin 1',
            'admin2' => 'Admin 2',
            'admin3' => 'Admin 3',
            'admin4' => 'Admin 4',
            'kasir' => 'Kasir',
            'gudang' => 'Gudang',
            'pasgar' => 'Pasgar',
            'sales' => 'Sales',
            'sales_gula' => 'Sales Gula',
            'sales_kanvas' => 'Sales Kanvas',
            'sales_minyak' => 'Sales Minyak',
            'sales_mineral' => 'Sales Mineral',
            'pending' => 'Pending (Menunggu ACC)',
        ];

        $now = now();
        DB::table('app_roles')->insert(
            collect($defaults)->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'description' => null,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ])->values()->all()
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('app_roles');
    }
};

