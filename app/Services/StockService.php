<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Log;

/**
 * StockService — Centralized stock management logic.
 * Eliminates code duplication across KasirEceranController,
 * Api/Sales/OrderController, and other controllers.
 */
class StockService
{
    /**
     * Validate that all items have sufficient stock.
     * Uses lockForUpdate() to prevent race conditions.
     *
     * @param  array  $items  [['product_id' => int, 'quantity' => int], ...]
     * @return array|null  Returns error array if insufficient, null if OK
     */
    public static function validateStock(array $items): ?array
    {
        foreach ($items as $item) {
            $product = Product::lockForUpdate()->find($item['product_id']);
            if (!$product || $product->stock < $item['quantity']) {
                return [
                    'product' => $product?->name ?? 'ID:' . $item['product_id'],
                    'available' => $product?->stock ?? 0,
                    'requested' => $item['quantity'],
                ];
            }
        }
        return null;
    }

    /**
     * Validate stock from a specific warehouse (for vehicle/canvas orders).
     *
     * @param  array  $items
     * @param  int    $warehouseId
     * @return array|null
     */
    public static function validateWarehouseStock(array $items, int $warehouseId): ?array
    {
        foreach ($items as $item) {
            $stock = ProductStock::where('product_id', $item['product_id'])
                ->where('warehouse_id', $warehouseId)
                ->lockForUpdate()
                ->first();

            $available = $stock?->stock ?? 0;
            if ($available < $item['quantity']) {
                $product = Product::find($item['product_id']);
                return [
                    'product'   => $product?->name ?? 'ID:' . $item['product_id'],
                    'available' => $available,
                    'requested' => $item['quantity'],
                ];
            }
        }
        return null;
    }

    /**
     * Deduct global product stock (products.stock column).
     *
     * @param  int  $productId
     * @param  int  $quantity
     */
    public static function deductGlobalStock(int $productId, int $quantity): void
    {
        Product::where('id', $productId)->decrement('stock', $quantity);
    }

    /**
     * Restore global product stock (for void/return).
     *
     * @param  int  $productId
     * @param  int  $quantity
     */
    public static function restoreGlobalStock(int $productId, int $quantity): void
    {
        Product::where('id', $productId)->increment('stock', $quantity);
    }

    /**
     * Deduct stock from product_stocks (per-warehouse) using FIFO.
     * Also records StockMovement for each deduction.
     *
     * @param  int     $productId
     * @param  int     $qty
     * @param  string  $reference     Reference number (TRX-xxx, SO-xxx, etc.)
     * @param  string  $sourceType    'pos_transaction', 'sales_order', etc.
     * @param  string  $notes
     * @param  int     $userId
     */
    public static function deductWarehouseStockFIFO(
        int $productId,
        int $qty,
        string $reference,
        string $sourceType,
        string $notes,
        int $userId
    ): void {
        $remaining = $qty;

        $stocks = ProductStock::where('product_id', $productId)
            ->where('stock', '>', 0)
            ->orderBy('expired_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break;

            $deduct = min($stock->stock, $remaining);
            $stock->stock -= $deduct;
            $stock->save();

            StockMovement::create([
                'product_id'       => $productId,
                'warehouse_id'     => $stock->warehouse_id,
                'location_id'      => $stock->location_id,
                'type'             => 'out',
                'source_type'      => $sourceType,
                'reference_number' => $reference,
                'quantity'         => $deduct,
                'balance'          => $stock->stock,
                'notes'            => $notes,
                'user_id'          => $userId,
            ]);

            $remaining -= $deduct;
        }

        if ($remaining > 0) {
            Log::warning("StockService::deductWarehouseStockFIFO — Could not fully deduct stock.", [
                'product_id' => $productId,
                'requested'  => $qty,
                'undeducted' => $remaining,
                'reference'  => $reference,
            ]);
        }
    }

    /**
     * Deduct stock from a SPECIFIC warehouse (for vehicle/canvas orders).
     * Does NOT fall through to other warehouses.
     *
     * @param  int     $productId
     * @param  int     $qty
     * @param  int     $warehouseId
     * @param  string  $reference
     * @param  string  $sourceType
     * @param  string  $notes
     * @param  int     $userId
     */
    public static function deductSpecificWarehouseStock(
        int $productId,
        int $qty,
        int $warehouseId,
        string $reference,
        string $sourceType,
        string $notes,
        int $userId
    ): void {
        $stock = ProductStock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->lockForUpdate()
            ->first();

        if (!$stock || $stock->stock < $qty) {
            Log::error("StockService::deductSpecificWarehouseStock — Insufficient stock in warehouse.", [
                'product_id'   => $productId,
                'warehouse_id' => $warehouseId,
                'requested'    => $qty,
                'available'    => $stock?->stock ?? 0,
            ]);
            return;
        }

        $stock->stock -= $qty;
        $stock->save();

        StockMovement::create([
            'product_id'       => $productId,
            'warehouse_id'     => $warehouseId,
            'location_id'      => $stock->location_id,
            'type'             => 'out',
            'source_type'      => $sourceType,
            'reference_number' => $reference,
            'quantity'         => $qty,
            'balance'          => $stock->stock,
            'notes'            => $notes,
            'user_id'          => $userId,
        ]);
    }

    /**
     * Restore stock to a specific warehouse (for void/return).
     *
     * @param  int     $productId
     * @param  int     $qty
     * @param  int|null $warehouseId
     * @param  string  $reference
     * @param  string  $notes
     * @param  int     $userId
     */
    public static function restoreWarehouseStock(
        int $productId,
        int $qty,
        ?int $warehouseId,
        string $reference,
        string $notes,
        int $userId
    ): void {
        if (!$warehouseId) {
            // Fallback: restore to first available warehouse
            $stock = ProductStock::where('product_id', $productId)
                ->orderBy('created_at', 'asc')
                ->first();
        } else {
            $stock = ProductStock::where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->first();
        }

        if ($stock) {
            $stock->stock += $qty;
            $stock->save();

            StockMovement::create([
                'product_id'       => $productId,
                'warehouse_id'     => $stock->warehouse_id,
                'location_id'      => $stock->location_id,
                'type'             => 'in',
                'source_type'      => 'void_transaction',
                'reference_number' => $reference,
                'quantity'         => $qty,
                'balance'          => $stock->stock,
                'notes'            => $notes,
                'user_id'          => $userId,
            ]);
        }
    }
}
