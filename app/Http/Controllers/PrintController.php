<?php

namespace App\Http\Controllers;

use App\Models\CustomerCreditPayment;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\StoreSetting;
use App\Models\SupplierDebtPayment;
use App\Models\Transaction;

class PrintController extends Controller
{
    /**
     * Cetak Struk POS Eceran (Thermal 58mm)
     */
    public function printReceipt(Transaction $transaction)
    {
        $transaction->load(['details.product', 'user', 'customer']);
        $storeSetting = StoreSetting::current();

        return view('print.receipt', compact('transaction', 'storeSetting'));
    }

    /**
     * Cetak Faktur Penjualan Grosir (A4 — 3 Rangkap)
     */
    public function printFakturGrosir(Transaction $transaction)
    {
        $transaction->load(['details.product', 'user', 'customer']);
        $storeSetting = StoreSetting::current();

        return view('print.faktur_grosir', compact('transaction', 'storeSetting'));
    }

    /**
     * Cetak Faktur Pembelian (PO) - A4/A5
     */
    public function printPurchase(PurchaseOrder $order)
    {
        $order->load(['supplier', 'items.product', 'user']);

        return view('print.purchase', compact('order'));
    }

    /**
     * Cetak Faktur Retur Pembelian - A4/A5
     */
    public function printReturn(PurchaseReturn $retur)
    {
        $retur->load(['supplier', 'items.product', 'user']);

        return view('print.return', compact('retur'));
    }

    /**
     * Cetak Bukti Pembayaran Hutang Supplier - A4/A5
     */
    public function printSupplierPayment(SupplierDebtPayment $payment)
    {
        $payment->load(['supplierDebt.supplier', 'createdBy']);

        return view('print.supplier_payment', compact('payment'));
    }

    /**
     * Cetak Bukti Pembayaran Piutang Pelanggan - A4/A5
     */
    public function printCustomerCredit(CustomerCreditPayment $kredit)
    {
        // Parameter di route bernama {kredit} yang aslinya CustomerCredit, tp kita mau cetak pembayaran atau detail kredit?
        // Tergantung kebutuhan. Di route kita arahkan ke payment atau credit id. Update plan: Cetak detail CustomerCredit saja supaya bisa sekalian cetak sisa hutang.

        $kredit->load(['customerCredit.customer', 'createdBy']);

        return view('print.customer_credit_payment', compact('kredit'));
    }
}
