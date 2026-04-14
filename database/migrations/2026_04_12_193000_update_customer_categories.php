<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update semua pelanggan yang masih 'pos' menjadi 'eceran'
        // User bisa manually update yang seharusnya 'grosir' nanti
        DB::table('customers')
            ->where('category', 'pos')
            ->orWhereNull('category')
            ->update(['category' => 'eceran']);
    }

    public function down(): void
    {
        // Revert back to 'pos' if needed
        DB::table('customers')
            ->where('category', 'eceran')
            ->update(['category' => 'pos']);
    }
};
