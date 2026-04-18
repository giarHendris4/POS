<div class="h-screen flex flex-col bg-gray-100">
    {{-- Header --}}
    <div class="bg-white shadow p-4">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold">POS - {{ $currentTenant->name ?? 'Kasir' }}</h1>
            <div class="text-sm text-gray-600">
                {{ now()->format('d M Y H:i') }} WIB
            </div>
        </div>
    </div>

    {{-- Barcode Scanner Input (Hidden) --}}
    <div class="p-4">
        <input type="text" 
               id="barcode-input" 
               wire:model="barcode" 
               class="w-full p-4 text-lg border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
               placeholder="Scan barcode di sini..."
               autofocus
               x-ref="barcodeInput"
               wire:keydown.enter="addToCart($event.target.value)"
               x-init="$refs.barcodeInput.focus()">
    </div>

    {{-- Cart Items --}}
    <div class="flex-1 overflow-y-auto p-4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-3 text-left">Produk</th>
                        <th class="p-3 text-center w-24">Qty</th>
                        <th class="p-3 text-right w-32">Harga</th>
                        <th class="p-3 text-right w-32">Total</th>
                        <th class="p-3 text-center w-16"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cartItems as $item)
                    <tr class="border-b">
                        <td class="p-3">
                            <div class="font-medium">{{ $item->product->name }}</div>
                            <div class="text-sm text-gray-500">{{ $item->product->barcode }}</div>
                        </td>
                        <td class="p-3">
                            <div class="flex items-center justify-center">
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                        class="w-8 h-8 bg-gray-200 rounded-l">-</button>
                                <input type="number" 
                                       value="{{ $item->quantity }}" 
                                       class="w-12 h-8 text-center border-t border-b"
                                       min="1"
                                       readonly>
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                                        class="w-8 h-8 bg-gray-200 rounded-r">+</button>
                            </div>
                        </td>
                        <td class="p-3 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="p-3 text-right font-medium">Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                        <td class="p-3 text-center">
                            <button wire:click="removeItem({{ $item->id }})" class="text-red-500">&times;</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500">
                            Keranjang kosong. Scan barcode untuk memulai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer --}}
    <div class="bg-white shadow-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <div>
                <span class="text-gray-600">Total Item:</span>
                <span class="font-bold text-lg ml-2">{{ $totalItems }}</span>
            </div>
            <div>
                <span class="text-gray-600">Subtotal:</span>
                <span class="font-bold text-2xl ml-2 text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="flex space-x-3">
            <button wire:click="clearCart" 
                    class="flex-1 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300"
                    @if($totalItems === 0) disabled @endif>
                Kosongkan
            </button>
            <button wire:click="openPaymentModal" 
                    class="flex-1 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700"
                    @if($totalItems === 0) disabled @endif>
                Bayar (Rp {{ number_format($subtotal, 0, ',', '.') }})
            </button>
        </div>
    </div>

    {{-- Payment Modal --}}
    @if($showPaymentModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="$set('showPaymentModal', false)">
        <div class="bg-white rounded-lg w-96 p-6" wire:click.stop>
            <h2 class="text-xl font-bold mb-4">Pembayaran</h2>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Total Tagihan</label>
                <div class="text-2xl font-bold text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Metode Pembayaran</label>
                <select wire:model.live="paymentMethod" class="w-full p-2 border rounded">
                    <option value="cash">Tunai</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            @if($paymentMethod === 'cash')
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Jumlah Bayar</label>
                <input type="number" 
                       wire:model.live="paymentAmount" 
                       class="w-full p-2 border rounded text-lg"
                       placeholder="Masukkan jumlah bayar"
                       min="{{ $subtotal }}">
                
                @if($paymentAmount > 0)
                <div class="mt-2 text-lg">
                    <span class="text-gray-600">Kembalian:</span>
                    <span class="font-bold ml-2 {{ $changeAmount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($changeAmount, 0, ',', '.') }}
                    </span>
                </div>
                @endif
            </div>
            @endif

            <div class="flex space-x-3">
                <button wire:click="$set('showPaymentModal', false)" 
                        class="flex-1 py-2 bg-gray-200 rounded">
                    Batal
                </button>
                <button wire:click="processPayment" 
                        class="flex-1 py-2 bg-green-600 text-white rounded"
                        @if($paymentMethod === 'cash' && $paymentAmount < $subtotal) disabled @endif>
                    Proses
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Add Product Modal --}}
    @if($showQuickAddModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="$set('showQuickAddModal', false)">
        <div class="bg-white rounded-lg w-96 p-6" wire:click.stop>
            <h2 class="text-xl font-bold mb-4">Tambah Produk Baru</h2>
            <p class="text-sm text-gray-600 mb-4">Barcode: {{ $newProductBarcode }}</p>
            
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Nama Produk</label>
                <input type="text" wire:model="newProductName" class="w-full p-2 border rounded">
                @error('newProductName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Harga Beli</label>
                <input type="number" wire:model="newProductCostPrice" class="w-full p-2 border rounded">
                @error('newProductCostPrice') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Harga Jual</label>
                <input type="number" wire:model="newProductSellingPrice" class="w-full p-2 border rounded">
                @error('newProductSellingPrice') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex space-x-3">
                <button wire:click="$set('showQuickAddModal', false)" 
                        class="flex-1 py-2 bg-gray-200 rounded">
                    Batal
                </button>
                <button wire:click="quickAddProduct" 
                        class="flex-1 py-2 bg-blue-600 text-white rounded">
                    Simpan
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Alpine.js for auto-focus --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('cart-updated', () => {
                setTimeout(() => {
                    const input = document.getElementById('barcode-input');
                    if (input) {
                        input.focus();
                        input.value = '';
                    }
                }, 50);
            });
            
            Livewire.on('checkout-complete', () => {
                setTimeout(() => {
                    const input = document.getElementById('barcode-input');
                    if (input) {
                        input.focus();
                    }
                }, 100);
            });

            Livewire.on('confirm-clear-cart', () => {
                if (confirm('Yakin ingin mengosongkan keranjang?')) {
                    Livewire.dispatch('force-clear-cart');
                }
            });
        });
    </script>

    {{-- Receipt Modal --}}
    <livewire:pos.receipt-modal />
</div>
