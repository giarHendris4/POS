<div class="h-screen flex flex-col bg-gray-50">
    <!-- Header - Sticky -->
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 safe-top">
        <div class="px-4 py-3">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-cash-register text-blue-600 mr-2"></i>
                        POS
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-600">{{ $currentTenant->name ?? 'Kasir' }}</p>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500">
                        <i class="far fa-clock mr-1"></i>{{ now()->format('H:i') }} WIB
                    </div>
                    <div class="text-xs text-gray-500">{{ now()->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barcode Scanner Input -->
    <div class="px-4 py-3 bg-white border-b border-gray-200">
        <div class="relative">
            <i class="fas fa-barcode absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" 
                   id="barcode-input" 
                   wire:model="barcode" 
                   class="w-full pl-10 pr-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                   placeholder="Scan atau ketik barcode..."
                   autofocus
                   x-ref="barcodeInput"
                   wire:keydown.enter="addToCart($event.target.value)"
                   x-init="$refs.barcodeInput.focus()">
        </div>
        <p class="text-xs text-gray-400 mt-1">
            <i class="fas fa-info-circle mr-1"></i>Scan barcode atau ketik manual lalu tekan Enter
        </p>
    </div>

    <!-- Cart Items - Scrollable -->
    <div class="flex-1 overflow-y-auto px-4 py-3 scrollbar-hide">
        @if(count($cartItems) > 0)
            <div class="space-y-3">
                @foreach($cartItems as $item)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-sm sm:text-base truncate">
                                {{ $item->product->name }}
                            </h3>
                            <p class="text-xs text-gray-500 font-mono mt-0.5">
                                {{ $item->product->barcode }}
                            </p>
                            <p class="text-sm text-blue-600 font-medium mt-1">
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </p>
                        </div>
                        <button wire:click="removeItem({{ $item->id }})" 
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg tap-target">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center bg-gray-100 rounded-lg">
                            <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                    class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded-l-lg tap-target">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <span class="w-12 text-center font-medium text-gray-900">{{ $item->quantity }}</span>
                            <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                                    class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded-r-lg tap-target">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Subtotal</p>
                            <p class="font-bold text-gray-900">
                                Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                <i class="fas fa-shopping-cart text-5xl mb-3 opacity-50"></i>
                <p class="text-base">Keranjang kosong</p>
                <p class="text-sm mt-1">Scan barcode untuk menambah produk</p>
            </div>
        @endif
    </div>

    <!-- Footer - Sticky -->
    <div class="bg-white border-t border-gray-200 safe-bottom sticky bottom-0 z-20">
        <div class="px-4 py-4">
            <!-- Summary -->
            <div class="flex justify-between items-center mb-3">
                <div>
                    <p class="text-sm text-gray-500">Total Item</p>
                    <p class="text-xl font-bold text-gray-900">{{ $totalItems }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Subtotal</p>
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <button wire:click="clearCart" 
                        class="py-3 px-4 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition tap-target"
                        @if($totalItems === 0) disabled @endif>
                    <i class="fas fa-trash mr-2"></i>Kosongkan
                </button>
                <button wire:click="openPaymentModal" 
                        class="py-3 px-4 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition tap-target"
                        @if($totalItems === 0) disabled @endif>
                    <i class="fas fa-credit-card mr-2"></i>Bayar
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    @if($showPaymentModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50" 
         wire:click="$set('showPaymentModal', false)">
        <div class="bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl p-6 safe-bottom" 
             wire:click.stop>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Pembayaran</h2>
                <button wire:click="$set('showPaymentModal', false)" class="p-2 text-gray-400 hover:text-gray-600 tap-target">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Tagihan</span>
                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="flex items-center justify-center p-3 border rounded-xl cursor-pointer tap-target
                                  {{ $paymentMethod === 'cash' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="cash" class="sr-only">
                        <i class="fas fa-money-bill-wave mr-2 {{ $paymentMethod === 'cash' ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        <span>Tunai</span>
                    </label>
                    <label class="flex items-center justify-center p-3 border rounded-xl cursor-pointer tap-target
                                  {{ $paymentMethod === 'transfer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="transfer" class="sr-only">
                        <i class="fas fa-university mr-2 {{ $paymentMethod === 'transfer' ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        <span>Transfer</span>
                    </label>
                    <label class="flex items-center justify-center p-3 border rounded-xl cursor-pointer tap-target
                                  {{ $paymentMethod === 'qris' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="qris" class="sr-only">
                        <i class="fas fa-qrcode mr-2 {{ $paymentMethod === 'qris' ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        <span>QRIS</span>
                    </label>
                </div>
            </div>

            @if($paymentMethod === 'cash')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" 
                           wire:model.live="paymentAmount" 
                           class="w-full pl-12 pr-4 py-3 text-lg border-2 border-gray-200 rounded-xl focus:border-blue-500 outline-none"
                           placeholder="0"
                           min="{{ $subtotal }}">
                </div>
                
                @if($paymentAmount > 0)
                <div class="mt-3 p-3 bg-gray-50 rounded-xl">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Kembalian</span>
                        <span class="text-xl font-bold {{ $changeAmount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($changeAmount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <button wire:click="processPayment" 
                    class="w-full py-4 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition tap-target"
                    @if($paymentMethod === 'cash' && $paymentAmount < $subtotal) disabled @endif>
                <i class="fas fa-check-circle mr-2"></i>Proses Pembayaran
            </button>
        </div>
    </div>
    @endif

    <!-- Quick Add Product Modal -->
    @if($showQuickAddModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50"
         wire:click="$set('showQuickAddModal', false)">
        <div class="bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl p-6 safe-bottom"
             wire:click.stop>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Tambah Produk Baru</h2>
                <button wire:click="$set('showQuickAddModal', false)" class="p-2 text-gray-400 hover:text-gray-600 tap-target">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="bg-blue-50 rounded-xl p-3 mb-4">
                <p class="text-sm text-blue-800">Barcode: <span class="font-mono font-bold">{{ $newProductBarcode }}</span></p>
            </div>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" wire:model="newProductName" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 outline-none"
                           placeholder="Contoh: Indomie Goreng">
                    @error('newProductName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
                    <input type="number" wire:model="newProductCostPrice" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 outline-none"
                           placeholder="0">
                    @error('newProductCostPrice') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                    <input type="number" wire:model="newProductSellingPrice" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 outline-none"
                           placeholder="0">
                    @error('newProductSellingPrice') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-6">
                <button wire:click="$set('showQuickAddModal', false)" 
                        class="py-3 px-4 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition tap-target">
                    Batal
                </button>
                <button wire:click="quickAddProduct" 
                        class="py-3 px-4 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition tap-target">
                    Simpan
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Receipt Modal -->
    <livewire:pos.receipt-modal />

    <!-- Scripts -->
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
</div>
