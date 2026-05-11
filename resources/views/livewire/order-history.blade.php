<div class="p-4 sm:p-6 space-y-4 sm:space-y-5" style="font-family: 'Inter', sans-serif;">
    {{-- Header --}}
    <div>
        <h1 class="text-xl sm:text-2xl font-black text-gray-900">Riwayat Transaksi</h1>
        <p class="text-sm text-gray-500 mt-0.5">Semua data penjualan dan struk yang tercatat</p>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Order</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/70 transition-colors duration-150">
                        <td class="px-5 py-4">
                            <span class="text-sm font-bold text-gray-900 font-mono">{{ $order->order_number }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <div>
                                <p class="text-sm text-gray-700 font-medium">{{ $order->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <span class="text-sm text-gray-600">{{ $order->user->name ?? 'Kasir' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 uppercase tracking-wide">
                                {{ $order->payment_method }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($order->status == 'completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Selesai
                                </span>
                            @elseif($order->status == 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button wire:click="viewDetails({{ $order->id }})"
                                class="flex items-center gap-1.5 ml-auto px-3 py-1.5 bg-gray-50 hover:bg-blue-50 text-gray-600 hover:text-blue-600 border border-gray-200 hover:border-blue-200 text-xs font-semibold rounded-lg transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-16 text-center"><p class="text-sm font-medium text-gray-400">Belum ada transaksi.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())<div class="px-5 py-3 border-t border-gray-100">{{ $orders->links() }}</div>@endif
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden space-y-3">
        @forelse($orders as $order)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div>
                        <p class="text-sm font-black text-gray-900 font-mono">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }} · {{ $order->user->name ?? 'Kasir' }}</p>
                    </div>
                    @if($order->status == 'completed')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 flex-shrink-0"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Selesai</span>
                    @elseif($order->status == 'pending')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 flex-shrink-0"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>Pending</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 flex-shrink-0"><span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>{{ ucfirst($order->status) }}</span>
                    @endif
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div>
                        <p class="text-lg font-black text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full uppercase font-semibold">{{ $order->payment_method }}</span>
                    </div>
                    <button wire:click="viewDetails({{ $order->id }})"
                        class="flex items-center gap-1.5 px-4 py-2 bg-gray-50 hover:bg-blue-50 text-gray-600 hover:text-blue-600 border border-gray-200 hover:border-blue-200 text-sm font-semibold rounded-xl transition-all">
                        Detail
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-16"><p class="text-sm font-medium text-gray-400">Belum ada transaksi.</p></div>
        @endforelse
        @if($orders->hasPages())<div class="pt-2">{{ $orders->links() }}</div>@endif
    </div>


    {{-- Modal Detail / Struk --}}
    @if($isModalOpen && $selectedOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeModal"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md border border-gray-100 overflow-hidden" id="receipt-content">
            {{-- Receipt Header --}}
            <div class="px-6 pt-6 pb-5 border-b border-dashed border-gray-200 text-center">
                <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-md shadow-orange-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 tracking-tight">WARUNGGALIH</h3>
                <p class="text-xs text-gray-400 mt-1">Struk Pembelian</p>

                <div class="mt-4 space-y-1 text-left bg-gray-50 rounded-xl px-4 py-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">No. Order</span>
                        <span class="font-bold text-gray-900 font-mono">{{ $selectedOrder->order_number }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="text-gray-700">{{ $selectedOrder->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Kasir</span>
                        <span class="text-gray-700">{{ $selectedOrder->user->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="px-6 py-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-400 uppercase border-b border-gray-100">
                            <th class="pb-2 font-semibold">Item</th>
                            <th class="pb-2 font-semibold text-center">Qty</th>
                            <th class="pb-2 font-semibold text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($selectedOrder->items as $item)
                        <tr>
                            <td class="py-2.5">
                                <p class="font-semibold text-gray-800">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                                <p class="text-xs text-gray-400">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-2.5 text-center font-bold text-gray-700">{{ $item->quantity }}</td>
                            <td class="py-2.5 text-right font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Total --}}
            <div class="px-6 pb-5 border-t border-dashed border-gray-200 pt-4 space-y-2">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($selectedOrder->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-1 border-t border-gray-100 mt-1">
                    <span class="font-black text-gray-900">TOTAL</span>
                    <span class="text-xl font-black text-orange-500">Rp {{ number_format($selectedOrder->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Metode Bayar</span>
                    <span class="font-semibold text-gray-700 uppercase">{{ $selectedOrder->payment_method }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                <button type="button" wire:click="closeModal"
                    class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-50 transition active:scale-95">
                    Tutup
                </button>
                <button type="button" onclick="printReceipt()"
                    class="flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold text-sm rounded-xl transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Struk
                </button>
            </div>
        </div>
    </div>

    <script>
        function printReceipt() {
            var printContents = document.getElementById('receipt-content').innerHTML;
            var printWindow = window.open('', '', 'height=600,width=400');
            printWindow.document.write('<html><head><title>Cetak Struk</title>');
            printWindow.document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
            printWindow.document.write('</head><body class="p-4" onload="window.print(); window.close();">');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
        }
    </script>
    @endif
</div>
