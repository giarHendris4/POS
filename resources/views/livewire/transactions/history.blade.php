<div class="min-h-screen bg-gray-50">
    <!-- Loading Indicator -->
    <div wire:loading class="fixed top-0 left-0 w-full h-1 bg-blue-500 animate-pulse z-50"></div>
    
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 safe-top">
        <div class="px-4 py-3">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-history text-blue-600 mr-2"></i>
                History Transaksi
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 mt-0.5">
                {{ $currentTenant->name ?? '' }}
            </p>
        </div>
    </div>

    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
            <div class="flex flex-wrap items-center gap-4 mb-3">
                <label class="flex items-center tap-target">
                    <input type="radio" wire:model.live="filterType" value="range" class="w-5 h-5 text-blue-600">
                    <span class="ml-2 text-sm">Rentang Tanggal</span>
                </label>
                <label class="flex items-center tap-target">
                    <input type="radio" wire:model.live="filterType" value="today" class="w-5 h-5 text-blue-600">
                    <span class="ml-2 text-sm">Tanggal Tertentu</span>
                </label>
            </div>

            @if($filterType === 'range')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" wire:model.live="startDate" 
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" wire:model.live="endDate" 
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-500 outline-none">
                </div>
            </div>
            @else
            <div>
                <label class="block text-xs text-gray-500 mb-1">Pilih Tanggal</label>
                <input type="date" wire:model.live="selectedDate" 
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-blue-500 outline-none">
            </div>
            @endif
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-blue-600 font-medium">Total Transaksi</p>
                        <p class="text-xl sm:text-2xl font-bold text-blue-900 mt-1">{{ $totalTransactions }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-blue-700"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-green-600 font-medium">Total Omzet</p>
                        <p class="text-lg sm:text-xl font-bold text-green-900 mt-1 truncate">
                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-green-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-700"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200 col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-purple-600 font-medium">Rata-rata Transaksi</p>
                        <p class="text-lg sm:text-xl font-bold text-purple-900 mt-1">
                            Rp {{ $totalTransactions > 0 ? number_format($totalAmount / $totalTransactions, 0, ',', '.') : 0 }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-purple-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-700"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Mobile Card View -->
            <div class="lg:hidden divide-y divide-gray-100">
                @forelse($transactions as $transaction)
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-mono text-sm font-medium text-gray-900">{{ $transaction->invoice_number }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <i class="far fa-clock mr-1"></i>{{ $transaction->transaction_date->format('d M Y H:i') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($transaction->payment_method === 'cash') bg-green-100 text-green-800
                            @elseif($transaction->payment_method === 'transfer') bg-blue-100 text-blue-800
                            @else bg-purple-100 text-purple-800
                            @endif">
                            {{ ucfirst($transaction->payment_method) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-500">Kasir: {{ $transaction->user->name }}</p>
                            @if($transaction->branch)
                            <p class="text-xs text-gray-500">Cabang: {{ $transaction->branch->name }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900">
                                Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                            </p>
                            <button wire:click="$dispatch('show-receipt', {transaction_id: {{ $transaction->id }}})"
                                    class="text-blue-600 text-sm font-medium hover:underline mt-1 tap-target">
                                <i class="fas fa-receipt mr-1"></i>Lihat Struk
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Tidak ada transaksi pada periode ini</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cabang</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono text-sm">{{ $transaction->invoice_number }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $transaction->transaction_date->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $transaction->branch->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($transaction->payment_method === 'cash') bg-green-100 text-green-800
                                    @elseif($transaction->payment_method === 'transfer') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst($transaction->payment_method) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="$dispatch('show-receipt', {transaction_id: {{ $transaction->id }}})"
                                        class="text-blue-600 hover:underline text-sm">
                                    <i class="fas fa-receipt mr-1"></i>Struk
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                                <p>Tidak ada transaksi pada periode ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Receipt Modal -->
    <livewire:pos.receipt-modal />
</div>
