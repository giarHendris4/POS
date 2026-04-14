<?php

namespace App\Traits;

use App\Models\Transaction;

trait GeneratesInvoiceNumber
{
    protected function generateInvoiceNumber(int $tenantId): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        
        $lastInvoice = Transaction::where('tenant_id', $tenantId)
            ->whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastInvoice && preg_match('/INV-\d{8}-(\d{4})/', $lastInvoice->invoice_number, $matches)) {
            $sequence = (int) $matches[1] + 1;
        } else {
            $sequence = 1;
        }
        
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }
}
