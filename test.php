<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Product::with('unitConversions')->find(2);
$baseUnitId = (int) ($p->unitConversions->firstWhere('is_base_unit', true)?->unit_id ?? $p->unit_id ?? 0);
echo 'BaseUnitId=' . $baseUnitId . "\n";

$unitId = 4;
$conversionFactor = 1;
if ($unitId && $baseUnitId && $unitId !== $baseUnitId) {
    $uc = $p->unitConversions->firstWhere('unit_id', $unitId);
    if (! $uc) {
        throw new \RuntimeException("Satuan tidak valid untuk produk {$p->name}.");
    }
    $conversionFactor = max(1, (int) $uc->conversion_factor);
}
echo 'ConversionFactor=' . $conversionFactor . "\n";

