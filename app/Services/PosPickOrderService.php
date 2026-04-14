<?php

namespace App\Services;

use App\Models\PosPickOrder;
use App\Models\PosPickOrderItem;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosPickOrderService
{
    /**
     * Create a pick order from a completed POS transaction
     *
     * @param Transaction $transaction
     * @param string $posType 'eceran' or 'grosir'
     * @return PosPickOrder|null
     */
    public function createFromTransaction(Transaction $transaction, string $posType = 'eceran'): ?PosPickOrder
    {
        try {
            DB::beginTransaction();

            // Get default warehouse or first active warehouse
            $warehouse = Warehouse::where('active', true)->first();

            if (!$warehouse) {
                Log::warning('No active warehouse found for pick order creation');
                return null;
            }

            // Load transaction details with products
            $transaction->load('details.product.unitConversions.unit');

            // Create pick order
            $pickOrder = PosPickOrder::create([
                'pick_number' => PosPickOrder::generateNumber(),
                'transaction_id' => $transaction->id,
                'warehouse_id' => $warehouse->id,
                'status' => 'pending',
                'pos_type' => $posType,
                'requested_by' => Auth::id() ?? $transaction->created_by,
                'notes' => "Transaksi {$transaction->invoice_number} dari POS {$posType}",
            ]);

            // Create pick order items from transaction details
            foreach ($transaction->details as $detail) {
                // Calculate unit quantity and name based on unit conversion
                $unitQty = $detail->quantity;
                $unitName = 'pcs';
                $baseQty = $detail->quantity;

                // If product has unit conversions, determine the unit used
                if ($detail->product && $detail->product->unitConversions) {
                    $unitConversion = $detail->product->unitConversions
                        ->where('conversion_factor', '>', 1)
                        ->first();

                    if ($unitConversion) {
                        $unitName = $unitConversion->unit?->name ?? 'unit';
                        // If the detail quantity is in base units, convert to display unit
                        if ($detail->quantity >= $unitConversion->conversion_factor) {
                            $unitQty = $detail->quantity / $unitConversion->conversion_factor;
                            $baseQty = $detail->quantity;
                        }
                    }
                }

                PosPickOrderItem::create([
                    'pick_order_id' => $pickOrder->id,
                    'transaction_detail_id' => $detail->id,
                    'product_id' => $detail->product_id,
                    'quantity' => $baseQty,
                    'unit_qty' => $unitQty,
                    'unit_name' => $unitName,
                ]);
            }

            DB::commit();

            Log::info("Pick order {$pickOrder->pick_number} created for transaction {$transaction->invoice_number}");

            return $pickOrder;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to create pick order: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get pending pick orders count for notification badge
     *
     * @return int
     */
    public function getPendingCount(): int
    {
        return PosPickOrder::whereIn('status', ['pending', 'processing'])->count();
    }

    /**
     * Check if transaction already has a pick order
     *
     * @param int $transactionId
     * @return bool
     */
    public function hasPickOrder(int $transactionId): bool
    {
        return PosPickOrder::where('transaction_id', $transactionId)->exists();
    }
}
