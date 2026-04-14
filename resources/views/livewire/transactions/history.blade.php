<div class="p-6 bg-gray-100 min-h-screen">
    <div wire:loading class="fixed top-0 left-0 w-full h-1 bg-blue-500 animate-pulse"></div>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6">History Transaksi</h1>

            {{-- Filter Section --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4 mb-4">
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="filterType" value="range" class="mr-2">
                        <span>Rentang Tanggal</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="filterType" value="today" class="mr-2">
                        <span>Tanggal Tertentu</span>
                    </label>
                </div>

                @if($filterType === 'range')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" wire:model.live="startDate" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" wire:model.live="endDate" class="w-full p-2 border rounded">
                    </div>
                </div>
                @else
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Pilih Tanggal</label>
                    <input type="date" wire:model.live="selectedDate" class="w-full p-2 border rounded">
                </div>
                @endif
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm text-blue-600">Total Transaksi</div>
                    <div class="text-2xl font-bold text-blue-700">{{ $totalTransactions }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm text-green-600">Total Omzet</div>
                    <div class="text-2xl font-bold text-green-700">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-sm text-purple-600">Rata-rata Transaksi</div>
                    <div class="text-2xl font-bold text-purple-700">
                        Rp {{ $totalTransactions > 0 ? number_format($totalAmount / $totalTransactions, 0, ',', '.') : 0 }}
                    </div>
                </div>
            </div>

            {{-- Transactions Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Invoice</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Kasir</th>
                            <th class="p-3 text-left">Cabang</th>
                            <th class="p-3 text-left">Metode</th>
                            <th class="p-3 text-right">Total</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-mono">{{ $transaction->invoice_number }}</td>
                            <td class="p-3">{{ $transaction->transaction_date->format('d M Y H:i') }}</td>
                            <td class="p-3">{{ $transaction->user->name }}</td>
                            <td class="p-3">{{ $transaction->branch->name ?? '-' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($transaction->payment_method === 'cash') bg-green-100 text-green-800
                                    @elseif($transaction->payment_method === 'transfer') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst($transaction->payment_method) }}
                                </span>
                            </td>
                            <td class="p-3 text-right font-medium">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                <button wire:click="$dispatch('show-receipt', {transaction_id: {{ $transaction->id }}})" 
                                        class="text-blue-600 hover:underline text-sm">
                                    Lihat Struk
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    {{-- Receipt Modal --}}
    <livewire:pos.receipt-modal />
</div>
