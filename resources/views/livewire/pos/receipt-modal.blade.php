@if($showModal && $transaction)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-[400px] max-h-[80vh] overflow-hidden">
        <div class="p-4 border-b">
            <h2 class="text-lg font-bold">Struk Transaksi</h2>
            <p class="text-sm text-gray-600">{{ $transaction->invoice_number }}</p>
        </div>

        <div class="p-4 overflow-y-auto max-h-[400px] bg-gray-50">
            <pre class="text-xs font-mono whitespace-pre-wrap">{{ $receiptContent }}</pre>
        </div>

        <div class="p-4 border-t">
            <div class="grid grid-cols-2 gap-2 mb-3">
                @if($canPrintBluetooth)
                <button wire:click="printBluetooth" 
                        class="py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                    🖨️ Print Bluetooth
                </button>
                @endif

                @if($canPrintUsb)
                <button wire:click="printUsb" 
                        class="py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                    🔌 Print USB
                </button>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-2">
                <button wire:click="downloadTxt" 
                        class="py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
                    📥 Download TXT
                </button>
                <button wire:click="closeModal" 
                        class="py-2 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">
                    Tutup
                </button>
            </div>

            <p class="text-xs text-gray-500 mt-3 text-center">
                Untuk print Bluetooth/USB, pastikan printer terhubung
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('print-bluetooth', (data) => {
        if (navigator.bluetooth) {
            navigator.bluetooth.requestDevice({
                acceptAllDevices: true,
                optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb']
            }).then(device => {
                console.log('Bluetooth device selected:', device);
                alert('Printer Bluetooth terhubung!');
            }).catch(error => {
                console.error('Bluetooth error:', error);
                alert('Gagal menghubungkan ke printer Bluetooth.');
            });
        } else {
            alert('Browser tidak mendukung Bluetooth. Gunakan Download TXT.');
        }
    });

    Livewire.on('print-usb', (data) => {
        if (navigator.serial) {
            navigator.serial.requestPort().then(port => {
                console.log('USB port selected:', port);
                alert('Printer USB terhubung!');
            }).catch(error => {
                console.error('USB error:', error);
                alert('Gagal menghubungkan ke printer USB.');
            });
        } else {
            alert('Browser tidak mendukung Web Serial. Gunakan Download TXT.');
        }
    });

    Livewire.on('download-receipt', (data) => {
        const blob = new Blob([data.content], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = data.filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });
});
</script>
@endif
