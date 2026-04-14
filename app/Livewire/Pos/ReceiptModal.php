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
        if (!$this->transaction) {
            session()->flash('error', 'Data transaksi tidak ditemukan.');
            return;
        }
        
        $printerService = new ThermalPrinterService($this->transaction);
        $printerService->savePrintedReceipt();
        
        $this->dispatch('print-bluetooth', [
            'content' => $this->receiptContent,
        ]);
    }

    public function printUsb()
    {
        if (!$this->transaction) {
            session()->flash('error', 'Data transaksi tidak ditemukan.');
            return;
        }
        
        $printerService = new ThermalPrinterService($this->transaction);
        $printerService->savePrintedReceipt();
        
        $this->dispatch('print-usb', [
            'content' => $this->receiptContent,
        ]);
    }

    public function downloadTxt()
    {
        if (!$this->transaction) {
            session()->flash('error', 'Data transaksi tidak ditemukan.');
            return;
        }
        
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
        return isset($_SERVER['HTTP_USER_AGENT']) && 
               (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false ||
                strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false);
    }

    protected function detectUsbSupport(): bool
    {
        return isset($_SERVER['HTTP_USER_AGENT']) && 
               (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false ||
                strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false);
    }

    public function getCanPrintBluetoothProperty(): bool
    {
        return $this->detectBluetoothSupport();
    }

    public function getCanPrintUsbProperty(): bool
    {
        return $this->detectUsbSupport();
    }

    public function render()
    {
        return view('livewire.pos.receipt-modal');
    }
}
