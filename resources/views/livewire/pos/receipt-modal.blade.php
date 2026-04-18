<div>
@if($showModal && $transaction)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50" 
     wire:click="closeModal">
    <div class="bg-white w-full sm:max-w-sm sm:rounded-2xl rounded-t-2xl safe-bottom" 
         wire:click.stop>
        <div class="p-4 border-b flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Struk Transaksi</h2>
                <p class="text-xs text-gray-500 font-mono">{{ $transaction->invoice_number }}</p>
            </div>
            <button wire:click="closeModal" class="p-2 text-gray-400 hover:text-gray-600 tap-target">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-4 bg-gray-50 max-h-96 overflow-y-auto">
            <pre class="text-xs font-mono whitespace-pre-wrap text-gray-800">{{ $receiptContent }}</pre>
        </div>

        <div class="p-4 border-t">
            <div class="grid grid-cols-2 gap-2 mb-2">
                @if($canPrintBluetooth)
                <button wire:click="printBluetooth" 
                        class="py-3 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 tap-target">
                    <i class="fas fa-bluetooth mr-1"></i> Bluetooth
                </button>
                @endif

                @if($canPrintUsb)
                <button wire:click="printUsb" 
                        class="py-3 bg-green-600 text-white rounded-xl text-sm font-medium hover:bg-green-700 tap-target">
                    <i class="fas fa-usb mr-1"></i> USB
                </button>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-2">
                <button wire:click="downloadTxt" 
                        class="py-3 bg-gray-600 text-white rounded-xl text-sm font-medium hover:bg-gray-700 tap-target">
                    <i class="fas fa-download mr-1"></i> Download
                </button>
                <button wire:click="closeModal" 
                        class="py-3 bg-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-300 tap-target">
                    Tutup
                </button>
            </div>

            <p class="text-xs text-gray-500 text-center mt-3">
                Pastikan printer thermal terhubung
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
                alert('Printer Bluetooth terhubung!');
            }).catch(error => {
                alert('Gagal menghubungkan ke printer Bluetooth.');
            });
        } else {
            alert('Browser tidak mendukung Bluetooth. Gunakan Download TXT.');
        }
    });

    Livewire.on('print-usb', (data) => {
        if (navigator.serial) {
            navigator.serial.requestPort().then(port => {
                alert('Printer USB terhubung!');
            }).catch(error => {
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
</div>
