<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DATA COUNTS ===\n";
echo "Transactions:       " . DB::table('transactions')->count() . "\n";
echo "Transaction Details:" . DB::table('transaction_details')->count() . "\n";
echo "Pick Orders:        " . DB::table('pos_pick_orders')->count() . "\n";
echo "Pick Order Items:   " . DB::table('pos_pick_order_items')->count() . "\n";
echo "Product Stocks:     " . DB::table('product_stocks')->count() . "\n";
echo "Stock Movements:    " . DB::table('stock_movements')->count() . "\n";
echo "POS Sessions:       " . DB::table('pos_sessions')->count() . "\n";
echo "Pos Cash Movements: " . DB::table('pos_cash_movements')->count() . "\n";
echo "Products (total):   " . DB::table('products')->count() . "\n";
echo "Products w/ stock:  " . DB::table('products')->where('stock', '>', 0)->count() . "\n";
echo "\n";

// Check if --clean flag is passed
if (in_array('--clean', $argv ?? [])) {
    echo "=== CLEANING DATA ===\n";

    DB::beginTransaction();
    try {
        // 1. Delete pick order items first (child)
        $n = DB::table('pos_pick_order_items')->delete();
        echo "Deleted pick order items: {$n}\n";

        // 2. Delete pick orders
        $n = DB::table('pos_pick_orders')->delete();
        echo "Deleted pick orders: {$n}\n";

        // 3. Delete transaction details (child)
        $n = DB::table('transaction_details')->delete();
        echo "Deleted transaction details: {$n}\n";

        // 4. Delete transactions
        $n = DB::table('transactions')->delete();
        echo "Deleted transactions: {$n}\n";

        // 5. Delete stock movements
        $n = DB::table('stock_movements')->where('source_type', 'pos_transaction')->delete();
        echo "Deleted stock movements (pos): {$n}\n";

        // 6. Reset product stocks
        $n = DB::table('product_stocks')->delete();
        echo "Deleted product stocks: {$n}\n";

        // 7. Reset products.stock to 0
        $n = DB::table('products')->update(['stock' => 0]);
        echo "Reset product stock to 0: {$n} products\n";

        // 8. Delete POS sessions
        $n = DB::table('pos_sessions')->delete();
        echo "Deleted POS sessions: {$n}\n";

        // 9. Delete POS cash movements
        $n = DB::table('pos_cash_movements')->delete();
        echo "Deleted POS cash movements: {$n}\n";

        // 10. Delete adjustments stock movements too
        $n = DB::table('stock_movements')->delete();
        echo "Deleted ALL stock movements: {$n}\n";

        DB::commit();
        echo "\n=== CLEANING COMPLETE ===\n";
    } catch (Exception $e) {
        DB::rollBack();
        echo "ERROR: " . $e->getMessage() . "\n";
    }
} else {
    echo "Run with --clean flag to execute cleanup:\n";
    echo "  php clean_data.php --clean\n";
}
