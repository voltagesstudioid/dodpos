<?php

namespace App\Http\Controllers;

use App\Models\SupplierDebt;
use App\Models\SupplierDebtPayment;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierDebtController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierDebt::with(['supplier', 'purchaseOrder'])->latest();

        if ($request->search) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $debts     = $query->paginate(15)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();

        // Summary stats
        $totalUnpaid  = SupplierDebt::where('status', '!=', 'paid')->sum(DB::raw('total_amount - paid_amount'));
        $totalOverdue  = SupplierDebt::where('status', '!=', 'paid')
            ->where('due_date', '<', now())->sum(DB::raw('total_amount - paid_amount'));
        $countUnpaid  = SupplierDebt::whereIn('status', ['unpaid', 'partial'])->count();

        return view('pembelian.hutang.index', compact('debts', 'suppliers', 'totalUnpaid', 'totalOverdue', 'countUnpaid'));
    }

    public function create()
    {
        $suppliers     = Supplier::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::whereIn('status', ['received', 'partial'])
            ->with('supplier')->latest()->get();
        return view('pembelian.hutang.create', compact('suppliers', 'purchaseOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'       => 'required|exists:suppliers,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'invoice_number'    => 'nullable|string|unique:supplier_debts,invoice_number',
            'transaction_date'  => 'required|date',
            'due_date'          => 'nullable|date|after_or_equal:transaction_date',
            'total_amount'      => 'required|numeric|min:1',
            'notes'             => 'nullable|string',
        ]);

        SupplierDebt::create([
            'invoice_number'    => $request->invoice_number ?: SupplierDebt::generateInvoiceNumber(),
            'supplier_id'       => $request->supplier_id,
            'purchase_order_id' => $request->purchase_order_id ?: null,
            'transaction_date'  => $request->transaction_date,
            'due_date'          => $request->due_date,
            'total_amount'      => $request->total_amount,
            'paid_amount'       => 0,
            'status'            => 'unpaid',
            'notes'             => $request->notes,
        ]);

        return redirect()->route('pembelian.hutang.index')->with('success', 'Hutang supplier berhasil dicatat.');
    }

    public function show(SupplierDebt $hutang)
    {
        $hutang->load(['supplier', 'purchaseOrder', 'payments.createdBy']);
        return view('pembelian.hutang.show', compact('hutang'));
    }

    public function pay(Request $request, SupplierDebt $hutang)
    {
        if ($hutang->status === 'paid') {
            return back()->with('error', 'Hutang ini sudah lunas.');
        }

        $request->validate([
            'payment_date'     => 'required|date',
            'amount'           => 'required|numeric|min:1|max:' . $hutang->remaining_amount,
            'payment_method'   => 'required|in:cash,transfer,check,other',
            'reference_number' => 'nullable|string',
            'notes'            => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            SupplierDebtPayment::create([
                'supplier_debt_id'  => $hutang->id,
                'payment_date'      => $request->payment_date,
                'amount'            => $request->amount,
                'payment_method'    => $request->payment_method,
                'reference_number'  => $request->reference_number,
                'notes'             => $request->notes,
                'created_by'        => Auth::id(),
            ]);

            $newPaid   = $hutang->paid_amount + $request->amount;
            $newStatus = $newPaid >= $hutang->total_amount ? 'paid' : 'partial';
            $hutang->update(['paid_amount' => $newPaid, 'status' => $newStatus]);

            DB::commit();
            $msg = $newStatus === 'paid' ? 'Hutang lunas! ✅' : 'Pembayaran Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil dicatat.';
            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    public function destroy(SupplierDebt $hutang)
    {
        if ($hutang->payments()->exists()) {
            return back()->with('error', 'Hutang yang sudah ada pembayarannya tidak bisa dihapus.');
        }
        $hutang->delete();
        return redirect()->route('pembelian.hutang.index')->with('success', 'Data hutang dihapus.');
    }
}
