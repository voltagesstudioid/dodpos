<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$items = App\Models\PurchaseOrderItem::with('product.unitConversions')->where('conversion_factor', 1)->get();
$fixed = 0;
foreach($items as $item) {
    if ($item->unit_id && $item->product) {
        $uc = $item->product->unitConversions->firstWhere('unit_id', $item->unit_id);
        if ($uc && $uc->conversion_factor > 1) {
            echo 'Fixing PO Item ID: ' . $item->id . ' | PO ID: ' . $item->purchase_order_id . ' | Product: ' . $item->product->name . ' | Unit: ' . $item->unit_id . ' | Should be: ' . $uc->conversion_factor . "\n";
            $item->conversion_factor = $uc->conversion_factor;
            $item->save();
            $fixed++;
        }
    }
}
echo "Fixed $fixed items.\n";
