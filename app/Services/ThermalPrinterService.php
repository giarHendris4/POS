<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\PrintedReceipt;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class ThermalPrinterService
{
    protected Transaction $transaction;
    protected Tenant $tenant;
    protected string $receiptContent = '';

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction->load(['items.product', 'user', 'tenant']);
        $this->tenant = $transaction->tenant;
    }

    public function generateReceipt(): string
    {
        $this->receiptContent = $this->buildHeader();
        $this->receiptContent .= $this->buildItems();
        $this->receiptContent .= $this->buildFooter();

        return $this->receiptContent;
    }

    protected function buildHeader(): string
    {
        $content = str_repeat('=', 48) . "\n";
        $content .= $this->centerText(strtoupper($this->tenant->name)) . "\n";
        
        if ($this->tenant->address) {
            $content .= $this->centerText($this->tenant->address) . "\n";
        }
        
        if ($this->tenant->phone) {
            $content .= $this->centerText('Telp: ' . $this->tenant->phone) . "\n";
        }
        
        $content .= str_repeat('=', 48) . "\n";
        $content .= sprintf("%-20s: %s\n", 'Tanggal', $this->transaction->transaction_date->format('d M Y H:i') . ' WIB');
        $content .= sprintf("%-20s: %s\n", 'Kasir', $this->transaction->user->name);
        $content .= sprintf("%-20s: %s\n", 'No. Invoice', $this->transaction->invoice_number);
        $content .= str_repeat('-', 48) . "\n";
        $content .= sprintf("%-20s %3s %8s %10s\n", 'Produk', 'Qty', 'Harga', 'Total');
        $content .= str_repeat('-', 48) . "\n";

        return $content;
    }

    protected function buildItems(): string
    {
        $content = '';
        
        foreach ($this->transaction->items as $item) {
            $productName = substr($item->product_name, 0, 18);
            $qty = $item->quantity;
            $price = number_format($item->unit_price_snapshot, 0, ',', '.');
            $total = number_format($item->subtotal, 0, ',', '.');
            
            $content .= sprintf("%-20s %3d %8s %10s\n", $productName, $qty, $price, $total);
        }
        
        $content .= str_repeat('-', 48) . "\n";

        return $content;
    }

    protected function buildFooter(): string
    {
        $content = '';
        $content .= sprintf("%-33s %15s\n", 'Subtotal', number_format($this->transaction->subtotal, 0, ',', '.'));
        
        if ($this->transaction->discount_amount > 0) {
            $content .= sprintf("%-33s %15s\n", 'Diskon', number_format($this->transaction->discount_amount, 0, ',', '.'));
        }
        
        if ($this->transaction->tax_amount > 0) {
            $content .= sprintf("%-33s %15s\n", 'Pajak', number_format($this->transaction->tax_amount, 0, ',', '.'));
        }
        
        $content .= sprintf("%-33s %15s\n", 'TOTAL', number_format($this->transaction->grand_total, 0, ',', '.'));
        $content .= str_repeat('-', 48) . "\n";
        $content .= sprintf("%-33s %15s\n", 'Bayar: ' . ucfirst($this->transaction->payment_method), number_format($this->transaction->payment_amount, 0, ',', '.'));
        $content .= sprintf("%-33s %15s\n", 'Kembali', number_format($this->transaction->change_amount, 0, ',', '.'));
        $content .= str_repeat('=', 48) . "\n";
        $content .= $this->centerText('TERIMA KASIH') . "\n";
        $content .= $this->centerText('Barang yang sudah dibeli') . "\n";
        $content .= $this->centerText('tidak dapat dikembalikan') . "\n";
        $content .= str_repeat('=', 48) . "\n\n\n\n";

        return $content;
    }

    protected function centerText(string $text, int $width = 48): string
    {
        $padding = max(0, $width - strlen($text));
        $leftPadding = floor($padding / 2);
        $rightPadding = $padding - $leftPadding;
        
        return str_repeat(' ', (int)$leftPadding) . $text . str_repeat(' ', (int)$rightPadding);
    }

    public function savePrintedReceipt(): PrintedReceipt
    {
        $receiptData = [
            'tenant_name' => $this->tenant->name,
            'tenant_address' => $this->tenant->address,
            'tenant_phone' => $this->tenant->phone,
            'invoice_number' => $this->transaction->invoice_number,
            'cashier_name' => $this->transaction->user->name,
            'transaction_date' => $this->transaction->transaction_date->toDateTimeString(),
            'items' => $this->transaction->items->map(function ($item) {
                return [
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price_snapshot,
                    'subtotal' => $item->subtotal,
                ];
            })->toArray(),
            'subtotal' => $this->transaction->subtotal,
            'discount' => $this->transaction->discount_amount,
            'tax' => $this->transaction->tax_amount,
            'grand_total' => $this->transaction->grand_total,
            'payment_method' => $this->transaction->payment_method,
            'payment_amount' => $this->transaction->payment_amount,
            'change_amount' => $this->transaction->change_amount,
        ];

        return PrintedReceipt::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->transaction->branch_id,
            'transaction_id' => $this->transaction->id,
            'receipt_data' => $receiptData,
            'total_amount' => $this->transaction->grand_total,
            'printer_type' => 'thermal',
            'printed_by' => Auth::id(),
            'printed_at' => now(),
        ]);
    }

    public function downloadAsText(): string
    {
        $content = $this->generateReceipt();
        $filename = 'struk-' . $this->transaction->invoice_number . '.txt';
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
