{{-- WARUNGGALIH POS — Mobile-First + Tablet UI --}}
<div class="flex flex-col md:flex-row h-[100dvh] bg-[#F7F8FA] overflow-hidden" style="font-family: 'Inter', sans-serif;"
    x-data="{ cartOpen: false }">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- ===== LEFT / TOP PANEL: Menu ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden min-h-0">

        {{-- Top Bar --}}
        <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100 shadow-sm">
            <div class="flex items-center">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-500 hidden sm:block">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Search --}}
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        placeholder="Cari menu..."
                        class="w-36 sm:w-52 pl-8 pr-3 py-2 text-sm bg-gray-100 border-0 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-400 placeholder-gray-400 transition">
                    <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                {{-- Mobile Cart Toggle Button --}}
                <button @click="cartOpen = !cartOpen"
                    class="md:hidden relative flex items-center justify-center w-9 h-9 bg-orange-500 rounded-xl text-white shadow-md flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    @if(!empty($cart))
                        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-xs font-black rounded-full flex items-center justify-center leading-none">
                            {{ array_sum(array_column($cart, 'quantity')) }}
                        </span>
                    @endif
                </button>
            </div>
        </div>

        {{-- Category Tabs --}}
        <div class="flex items-center gap-2.5 px-6 py-3.5 overflow-x-auto bg-white border-b border-gray-100 scrollbar-hide">
            @foreach($categories as $category)
                <button wire:click="setCategory({{ $category->id }})"
                    class="flex-shrink-0 px-4 py-1.5 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200
                    {{ $activeCategory === $category->id
                        ? 'bg-orange-500 text-white shadow-md'
                        : 'bg-gray-100 text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        {{-- Flash Error --}}
        @if (session()->has('error'))
            <div class="mx-4 mt-3 px-4 py-3 bg-red-50 border border-red-200 text-red-600 text-sm font-medium rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Product Grid --}}
        <div class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($products as $product)
                    <button wire:click="addToCart({{ $product->id }})"
                        @if($product->stock <= 0) disabled @endif
                        class="relative bg-white rounded-2xl overflow-hidden shadow-sm border border-transparent hover:border-orange-300 hover:shadow-lg active:scale-95 transition-all duration-200 text-left group
                        {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">

                        @if($product->stock <= 0)
                            <div class="absolute inset-0 bg-gray-800/40 z-10 flex items-center justify-center rounded-2xl">
                                <span class="bg-gray-800 text-white text-xs font-bold px-2 py-0.5 rounded-full">HABIS</span>
                            </div>
                        @endif

                        {{-- Image --}}
                        <div class="w-full h-24 sm:h-32 bg-gray-50 overflow-hidden">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&color=f97316&background=fff7ed&size=200' }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>

                        {{-- Info --}}
                        <div class="p-2.5">
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-800 line-clamp-1 mb-1">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between">
                                <span class="text-orange-500 font-bold text-xs sm:text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-400 {{ $product->stock < 5 && $product->stock > 0 ? 'text-red-400 font-semibold' : '' }} hidden sm:block">
                                    Sisa {{ $product->stock }}
                                </span>
                            </div>
                        </div>

                        {{-- Add Indicator --}}
                        <div class="absolute top-2 right-2 w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                    </button>
                @empty
                    <div class="col-span-4 flex flex-col items-center justify-center py-20 text-gray-400">
                        <svg class="w-14 h-14 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p class="text-sm font-medium">Tidak ada menu di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== CART: Desktop Sidebar + Mobile Bottom Sheet ===== --}}

    {{-- MOBILE: Backdrop overlay --}}
    <div x-show="cartOpen" @click="cartOpen = false"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="md:hidden fixed inset-0 bg-black/50 z-30 backdrop-blur-sm">
    </div>

    {{-- Cart Panel --}}
    <div
        x-bind:class="cartOpen ? 'translate-y-0' : 'translate-y-full md:translate-y-0'"
        class="fixed md:relative bottom-0 left-0 right-0 md:bottom-auto md:left-auto md:right-auto
               w-full md:w-80 xl:w-96
               bg-white border-t md:border-t-0 md:border-l border-gray-100
               flex flex-col shadow-2xl md:shadow-xl
               z-40 md:z-auto
               rounded-t-3xl md:rounded-none
               h-[85dvh] md:h-auto
               transition-transform duration-300 ease-out">

        {{-- Handle (mobile only) --}}
        <div class="flex justify-center pt-3 pb-1 md:hidden">
            <div class="w-10 h-1 bg-gray-200 rounded-full"></div>
        </div>

        {{-- Cart Header --}}
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h2 class="text-base font-bold text-gray-900">Keranjang</h2>
                @if(!empty($cart))
                    <span class="bg-orange-100 text-orange-600 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ array_sum(array_column($cart, 'quantity')) }} item
                    </span>
                @endif
            </div>
            {{-- Mobile close --}}
            <button @click="cartOpen = false" class="md:hidden w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto px-4 py-3 space-y-2 min-h-0">
            @forelse($cart as $index => $item)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl group hover:bg-orange-50 transition-colors">
                    {{-- Thumbnail --}}
                    <div class="w-11 h-11 rounded-xl bg-gray-200 overflow-hidden flex-shrink-0">
                        <img src="{{ $item['image'] ? asset('storage/'.$item['image']) : 'https://ui-avatars.com/api/?name='.urlencode($item['name']).'&color=f97316&background=fff7ed' }}"
                            class="w-full h-full object-cover">
                    </div>

                    {{-- Name & Price --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $item['name'] }}</p>
                        <p class="text-xs text-orange-500 font-bold">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>

                    {{-- Qty Controls --}}
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <button wire:click="decreaseQuantity({{ $index }})"
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-red-50 hover:border-red-200 hover:text-red-500 transition-colors font-bold text-base leading-none">
                            −
                        </button>
                        <span class="text-sm font-bold text-gray-800 w-6 text-center">{{ $item['quantity'] }}</span>
                        <button wire:click="increaseQuantity({{ $index }})"
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-green-50 hover:border-green-200 hover:text-green-500 transition-colors font-bold text-base leading-none">
                            +
                        </button>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full py-10 text-gray-400">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-3 border-2 border-dashed border-gray-200">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <p class="text-sm font-medium">Belum ada item</p>
                    <p class="text-xs text-gray-300 mt-1">Ketuk menu untuk menambah</p>
                </div>
            @endforelse
        </div>

        {{-- Order Summary & Checkout --}}
        <div class="px-4 py-4 border-t border-gray-100 space-y-3">

            @if (session()->has('message'))
                <div class="p-3 bg-green-50 border border-green-200 text-green-700 text-sm font-medium rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('message') }}
                </div>
            @endif

            {{-- Total --}}
            <div class="bg-gray-50 rounded-2xl px-4 py-3 space-y-1.5">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Diskon</span>
                    <span>Rp 0</span>
                </div>
                <div class="h-px bg-gray-200 my-1"></div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-800">Total</span>
                    <span class="text-lg font-black text-orange-500">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Checkout Button --}}
            <button wire:click="checkout"
                @if(empty($cart)) disabled @endif
                class="w-full py-3.5 rounded-2xl font-bold text-sm tracking-wide transition-all duration-200
                {{ empty($cart)
                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    : 'bg-orange-500 hover:bg-orange-600 active:scale-95 text-white shadow-lg shadow-orange-200' }}">
                @if(empty($cart))
                    Pilih menu dulu
                @else
                    Bayar · Rp {{ number_format($this->total, 0, ',', '.') }}
                @endif
            </button>
        </div>
    </div>

</div>
