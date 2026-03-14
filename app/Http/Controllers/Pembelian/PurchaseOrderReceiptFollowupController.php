<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderReceipt;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PurchaseOrderReceiptFollowupController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureSupervisor();

        $status = $request->input('status', 'open');
        if (! in_array($status, ['open', 'resolved', 'all'], true)) {
            $status = 'open';
        }

        $query = PurchaseOrderReceipt::with(['purchaseOrder.supplier', 'warehouse', 'receiver', 'resolver', 'purchaseReturn', 'reorderPurchaseOrder'])
            ->where('needs_followup', true)
            ->orderByDesc('created_at');

        if ($status !== 'all') {
            $query->where('followup_status', $status);
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('purchaseOrder', function ($po) use ($q) {
                    $po->where('po_number', 'like', '%'.$q.'%')
                        ->orWhereHas('supplier', fn ($s) => $s->where('name', 'like', '%'.$q.'%'));
                })
                    ->orWhereHas('warehouse', fn ($w) => $w->where('name', 'like', '%'.$q.'%'))
                    ->orWhereHas('receiver', fn ($u) => $u->where('name', 'like', '%'.$q.'%'));
            });
        }

        $receipts = $query->paginate(15)->withQueryString();

        return view('pembelian.receipts_followup.index', compact('receipts', 'status'));
    }

    public function show(PurchaseOrderReceipt $receipt)
    {
        $this->ensureSupervisor();

        $receipt->load(['purchaseOrder.supplier', 'warehouse', 'receiver', 'resolver', 'items.product', 'items.purchaseOrderItem.unit']);

        return view('pembelian.receipts_followup.show', compact('receipt'));
    }

    public function resolve(Request $request, PurchaseOrderReceipt $receipt)
    {
        $this->ensureSupervisor();

        $request->validate([
            'followup_action' => 'required|in:return_to_supplier,request_replacement,request_credit_note,accept_with_note,other',
            'followup_notes' => 'required|string|max:2000',
        ]);

        if (! $receipt->needs_followup) {
            return back()->with('error', 'Receipt ini tidak membutuhkan follow-up.');
        }

        $receipt->followup_status = 'resolved';
        $receipt->followup_action = $request->followup_action;
        $receipt->followup_notes = $request->followup_notes;
        $receipt->resolved_by = Auth::id();
        $receipt->resolved_at = now();
        $receipt->save();

        return redirect()->route('pembelian.receipts_followup.show', $receipt)->with('success', 'Follow-up berhasil disimpan.');
    }

    public function createReorderPo(PurchaseOrderReceipt $receipt)
    {
        $this->ensureSupervisor();

        $receipt->load(['purchaseOrder.supplier', 'purchaseOrder.items.product.unit']);

        if (! $receipt->needs_followup) {
            return back()->with('error', 'Receipt ini tidak membutuhkan follow-up.');
        }
        if ($receipt->reorder_purchase_order_id) {
            return redirect()->route('pembelian.order.edit', $receipt->reorder_purchase_order_id)
                ->with('success', 'Draft PO reorder sudah dibuat dari receipt ini.');
        }
        if (! $receipt->purchaseOrder) {
            return back()->with('error', 'PO tidak ditemukan.');
        }

        $po = $receipt->purchaseOrder;
        $remainingItems = $po->items
            ->map(function ($it) {
                $remaining = (int) $it->qty_ordered - (int) $it->qty_received;

                return (object) [
                    'product_id' => (int) $it->product_id,
                    'unit_id' => (int) $it->unit_id,
                    'conversion_factor' => (int) $it->conversion_factor,
                    'unit_price' => (float) $it->unit_price,
                    'qty' => max(0, $remaining),
                ];
            })
            ->filter(fn ($r) => $r->product_id > 0 && $r->qty > 0)
            ->values();

        if ($remainingItems->count() === 0) {
            return back()->with('error', 'Tidak ada item shortage yang perlu dibuat PO reorder.');
        }

        try {
            DB::beginTransaction();

            $receipt = PurchaseOrderReceipt::whereKey($receipt->id)->lockForUpdate()->first();
            if (! $receipt) {
                DB::rollBack();

                return back()->with('error', 'Receipt tidak ditemukan.');
            }
            if ($receipt->reorder_purchase_order_id) {
                DB::commit();

                return redirect()->route('pembelian.order.edit', $receipt->reorder_purchase_order_id);
            }

            $po = PurchaseOrder::whereKey($receipt->purchase_order_id)->lockForUpdate()->first();
            if (! $po) {
                DB::rollBack();

                return back()->with('error', 'PO tidak ditemukan.');
            }

            $payload = [
                'po_number' => PurchaseOrder::generatePoNumber(),
                'supplier_id' => $po->supplier_id,
                'status' => 'draft',
                'order_date' => now()->toDateString(),
                'expected_date' => null,
                'due_date' => null,
                'total_amount' => 0,
                'notes' => 'Auto reorder dari QC Receipt #'.$receipt->id.' (PO '.$po->po_number.')',
                'user_id' => Auth::id(),
            ];
            if (Schema::hasColumn('purchase_orders', 'payment_term')) {
                $payload['payment_term'] = $po->payment_term ?: 'credit';
                if (($payload['payment_term'] ?? null) === 'cash') {
                    $payload['due_date'] = null;
                }
            }

            $newPo = PurchaseOrder::create($payload);

            $total = 0.0;
            foreach ($remainingItems as $r) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $newPo->id,
                    'product_id' => $r->product_id,
                    'unit_id' => $r->unit_id ?: null,
                    'conversion_factor' => max(1, (int) $r->conversion_factor),
                    'qty_ordered' => (int) $r->qty,
                    'unit_price' => (float) $r->unit_price,
                ]);
                $total += ((int) $r->qty) * (float) $r->unit_price;
            }
            $newPo->total_amount = $total;
            $newPo->save();

            $receipt->reorder_purchase_order_id = $newPo->id;
            $receipt->followup_action = $receipt->followup_action ?: 'request_replacement';
            $receipt->save();

            DB::commit();

            return redirect()->route('pembelian.order.edit', $newPo)->with('success', 'Draft PO reorder berhasil dibuat dari receipt.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membuat draft PO reorder: '.$e->getMessage());
        }
    }

    public function createReturn(PurchaseOrderReceipt $receipt)
    {
        $this->ensureSupervisor();

        $receipt->load(['purchaseOrder.supplier', 'items.purchaseOrderItem']);

        if (! $receipt->needs_followup) {
            return back()->with('error', 'Receipt ini tidak membutuhkan follow-up.');
        }
        if ($receipt->purchase_return_id) {
            return redirect()->route('pembelian.retur.show', $receipt->purchase_return_id)
                ->with('success', 'Draft retur sudah dibuat dari receipt ini.');
        }
        if (! $receipt->purchaseOrder || ! $receipt->purchaseOrder->supplier_id) {
            return back()->with('error', 'Supplier tidak ditemukan pada PO.');
        }

        $items = $receipt->items->filter(function ($it) {
            $qty = (int) $it->qty_received_po_unit;
            if ($qty <= 0) {
                return false;
            }
            $qcFail = (! $it->quality_ok) || (! $it->spec_ok) || (! $it->packaging_ok);
            $rejected = ($it->result === 'rejected');

            return $qcFail || $rejected;
        })->values();

        if ($items->count() === 0) {
            return back()->with('error', 'Tidak ada item yang bisa dibuatkan retur dari receipt ini.');
        }

        try {
            DB::beginTransaction();

            $receipt = PurchaseOrderReceipt::whereKey($receipt->id)->lockForUpdate()->first();
            if (! $receipt) {
                DB::rollBack();

                return back()->with('error', 'Receipt tidak ditemukan.');
            }
            if ($receipt->purchase_return_id) {
                DB::commit();

                return redirect()->route('pembelian.retur.show', $receipt->purchase_return_id);
            }

            $po = $receipt->purchaseOrder()->with('supplier')->first();
            if (! $po) {
                DB::rollBack();

                return back()->with('error', 'PO tidak ditemukan.');
            }

            $return = PurchaseReturn::create([
                'return_number' => PurchaseReturn::generateNumber(),
                'supplier_id' => $po->supplier_id,
                'purchase_order_id' => $po->id,
                'warehouse_id' => $receipt->warehouse_id,
                'return_date' => now()->toDateString(),
                'status' => 'draft',
                'total_amount' => 0,
                'reason' => 'QC bermasalah dari penerimaan PO '.$po->po_number,
                'notes' => 'Auto dari QC Receipt #'.$receipt->id,
                'created_by' => Auth::id(),
            ]);

            $total = 0.0;

            foreach ($items as $it) {
                $poi = $it->purchaseOrderItem;
                $unitId = (int) ($poi?->unit_id ?? 0);
                $unitPrice = (float) ($poi?->unit_price ?? 0);
                $qty = (int) $it->qty_received_po_unit;
                if ($unitId <= 0 || $qty <= 0) {
                    continue;
                }

                $reasonParts = [];
                if ($it->result === 'rejected') {
                    $reasonParts[] = 'Rejected';
                }
                if (! $it->quality_ok) {
                    $reasonParts[] = 'Kualitas';
                }
                if (! $it->spec_ok) {
                    $reasonParts[] = 'Spesifikasi';
                }
                if (! $it->packaging_ok) {
                    $reasonParts[] = 'Kemasan';
                }
                $reason = implode(', ', $reasonParts);
                if ($it->notes) {
                    $reason = $reason ? ($reason.' | '.$it->notes) : $it->notes;
                }

                PurchaseReturnItem::create([
                    'purchase_return_id' => $return->id,
                    'product_id' => $it->product_id,
                    'unit_id' => $unitId,
                    'quantity' => $qty,
                    'purchase_price' => $unitPrice,
                    'reason' => $reason ?: null,
                ]);

                $total += ($qty * $unitPrice);
            }

            $return->total_amount = $total;
            $return->save();

            $receipt->purchase_return_id = $return->id;
            $receipt->followup_action = $receipt->followup_action ?: 'return_to_supplier';
            $receipt->save();

            DB::commit();

            return redirect()->route('pembelian.retur.show', $return)->with('success', 'Draft retur berhasil dibuat dari receipt.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membuat draft retur: '.$e->getMessage());
        }
    }

    private function ensureSupervisor(): void
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if ($role !== 'supervisor') {
            abort(403);
        }
    }
}
