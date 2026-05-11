<div class="min-h-screen bg-gray-900 p-6" wire:poll.3s style="font-family: 'Inter', sans-serif;">
    {{-- KDS Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-white tracking-tight">KITCHEN DISPLAY</h1>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-xs text-gray-400">Live · Auto-sync setiap 3 detik</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-gray-800 border border-gray-700 px-4 py-2.5 rounded-xl">
                <p class="text-xs text-gray-400 leading-none">Antrian Aktif</p>
                <p class="text-2xl font-black text-orange-400 leading-tight">{{ $orders->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Empty State --}}
    @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-gray-600">
            <div class="w-24 h-24 bg-gray-800 rounded-2xl flex items-center justify-center mb-5 border border-gray-700">
                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-500 tracking-widest uppercase mb-1">Dapur Kosong</h2>
            <p class="text-gray-600 text-sm">Menunggu pesanan masuk dari kasir...</p>
        </div>

    @else
        {{-- Kanban Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($orders as $order)
                <div class="rounded-2xl overflow-hidden flex flex-col transition-all duration-300
                    {{ $loop->first
                        ? 'bg-amber-50 border-2 border-amber-400 shadow-lg shadow-amber-400/20'
                        : 'bg-white border border-gray-200 shadow-md' }}">

                    {{-- Card Header --}}
                    <div class="px-4 py-3 flex items-center justify-between
                        {{ $loop->first ? 'bg-amber-400' : 'bg-gray-800' }}">
                        <div>
                            <p class="text-xs font-semibold {{ $loop->first ? 'text-amber-900' : 'text-gray-400' }} uppercase tracking-wider">Order</p>
                            <p class="text-sm font-black {{ $loop->first ? 'text-amber-900' : 'text-white' }} font-mono">{{ $order->order_number }}</p>
                        </div>
                        <div class="text-right">
                            @if($loop->first)
                                <span class="inline-flex items-center gap-1 bg-amber-900/20 text-amber-900 text-xs font-bold px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-amber-900 rounded-full animate-pulse"></span>
                                    PRIORITAS
                                </span>
                            @else
                                <span class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Queue Number --}}
                    <div class="text-center py-4 {{ $loop->first ? 'bg-amber-50' : 'bg-gray-50' }} border-b {{ $loop->first ? 'border-amber-200' : 'border-gray-100' }}">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">No. Antrian</p>
                        <p class="text-6xl font-black {{ $loop->first ? 'text-amber-500' : 'text-gray-800' }} leading-none">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('H:i') }}</p>
                    </div>

                    {{-- Items --}}
                    <div class="flex-1 p-4 bg-white">
                        <ul class="space-y-2.5">
                            @foreach($order->items as $item)
                                <li class="flex items-start gap-3 pb-2.5 border-b border-dashed border-gray-100 last:border-0 last:pb-0">
                                    <span class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center font-black text-sm
                                        {{ $loop->parent->first ? 'bg-amber-100 text-amber-700' : 'bg-orange-50 text-orange-600' }}">
                                        {{ $item->quantity }}x
                                    </span>
                                    <span class="text-base font-bold text-gray-800 leading-tight pt-0.5">
                                        {{ $item->product->name ?? 'Produk Dihapus' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Done Button --}}
                    <button wire:click="markAsCompleted({{ $order->id }})"
                        class="w-full py-4 font-black text-sm tracking-widest uppercase transition-all duration-200 active:scale-y-95
                        {{ $loop->first
                            ? 'bg-amber-500 hover:bg-amber-600 text-white'
                            : 'bg-gray-900 hover:bg-gray-800 text-white' }}">
                        ✓ &nbsp;Selesai Dimasak
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
