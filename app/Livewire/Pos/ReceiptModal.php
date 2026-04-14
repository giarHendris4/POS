<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Transaction;
use App\Services\ThermalPrinterService;

class ReceiptModal extends Component
{
    public $showModal = false;
    public $transaction = null;
    public $receiptContent = '';
    public $canPrintBluetooth = false;
    public $canPrintUsb = false;

    protected $listeners = [
        'show-receipt' => 'showReceipt',
    ];

    public function mount()
    {
        $this->canPrintBluetooth = $this->detectBluetoothSupport();
        $this->canPrintUsb = $this->detectUsbSupport();
    }

    public function showReceipt($data)
    {
        $this->transaction = Transaction::with(['items.product', 'user', 'tenant'])
            ->find($data['transaction_id']);
        
        if ($this->transaction) {
            $printerService = new ThermalPrinterService($this->transaction);
            $this->receiptContent = $printerService->generateReceipt();
            $this->showModal = true;
        }
    }

    public function printBluetooth()
    {
        $printerService = new ThermalPrinterService($this->transaction);
        $printerService->savePrintedReceipt();
        
        $this->dispatch('print-bluetooth', [
            'content' => $this->receiptContent,
        ]);
    }

    public function printUsb()
    {
        $printerService = new ThermalPrinterService($this->transaction);
        $printerService->savePrintedReceipt();
        
        $this->dispatch('print-usb', [
            'content' => $this->receiptContent,
        ]);
    }

    public function downloadTxt()
    {
        $printerService = new ThermalPrinterService($this->transaction);
        $printerService->savePrintedReceipt();
        
        $content = $printerService->generateReceipt();
        $filename = 'struk-' . $this->transaction->invoice_number . '.txt';
        
        $this->dispatch('download-receipt', [
            'content' => $content,
            'filename' => $filename,
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->transaction = null;
        $this->receiptContent = '';
    }

    protected function detectBluetoothSupport(): bool
    {
        return true;
    }

    protected function detectUsbSupport(): bool
    {
        return true;
    }

    public function render()
    {
        return view('livewire.pos.receipt-modal');
    }
}
