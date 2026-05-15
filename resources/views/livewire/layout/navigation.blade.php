<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/login');
    }
}; ?>

@php
    $storeLogo = \App\Models\Setting::get('store_logo');
    $storeName = \App\Models\Setting::get('store_name', 'Warunggalih POS');
    $navItems = [
        ['route' => 'dashboard',  'label' => 'Dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['route' => 'pos',        'label' => 'Kasir',       'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M12 7h.01M15 7h.01M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-4M9 21v-4a2 2 0 012-2h2a2 2 0 012 2v4'],
        ['route' => 'products',   'label' => 'Produk',      'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4'],
        ['route' => 'categories', 'label' => 'Kategori',    'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['route' => 'orders',     'label' => 'Transaksi',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
        ['route' => 'kitchen',    'label' => 'Dapur',       'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['route' => 'settings',   'label' => 'Pengaturan',  'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    ];

    if (auth()->check() && auth()->user()->is_admin) {
        $navItems[] = ['route' => 'users', 'label' => 'Pengguna', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'];
    }
@endphp

<nav x-data="{ mobileOpen: false, userOpen: false }" class="bg-white border-b border-gray-100 sticky top-0 z-40">
    <div class="max-w-[100rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">

            {{-- Left: Logo + Desktop Nav --}}
            <div class="flex items-center gap-6">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5 flex-shrink-0">
                    @if($storeLogo)
                        <img src="{{ asset('storage/' . $storeLogo) }}" alt="{{ $storeName }}" class="h-8 w-8 object-cover rounded-lg">
                    @else
                        <img src="{{ asset('logo.png') }}" alt="{{ $storeName }}" class="h-8 w-8 object-cover rounded-lg">
                    @endif
                    <span class="font-bold text-gray-900 text-sm hidden sm:block">{{ $storeName }}</span>
                </a>

                {{-- Desktop Nav Items --}}
                <div class="hidden lg:flex items-center gap-0.5">
                    @foreach($navItems as $nav)
                        <a href="{{ route($nav['route']) }}" wire:navigate
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                            {{ request()->routeIs($nav['route'])
                                ? 'bg-orange-50 text-orange-600 font-semibold'
                                : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $nav['icon'] }}"></path></svg>
                            <span>{{ $nav['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Right: User + Hamburger --}}
            <div class="flex items-center gap-2">

                {{-- Desktop User Dropdown --}}
                <div class="hidden lg:flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">{{ auth()->user()->name }}</span>
                </div>

                <div class="relative" x-data="{ userOpen: false }">
                    <button @click="userOpen = !userOpen"
                        class="hidden lg:flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-sm text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
                        <div class="w-7 h-7 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="userOpen" @click.outside="userOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 origin-top-right">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile') }}" wire:navigate
                            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profil Saya
                        </a>
                        <button wire:click="logout"
                            class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </div>
                </div>

                {{-- Mobile Hamburger --}}
                <button @click="mobileOpen = !mobileOpen"
                    class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100 transition">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Drawer --}}
    <div x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        @click.outside="mobileOpen = false"
        class="lg:hidden absolute top-14 left-0 right-0 bg-white border-b border-gray-100 shadow-xl z-50 px-4 pb-4 pt-2">

        {{-- User Info --}}
        <div class="flex items-center gap-3 px-3 py-3 mb-2 bg-gray-50 rounded-2xl">
            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        {{-- Nav Links Grid --}}
        <div class="grid grid-cols-2 gap-1.5 mb-2">
            @foreach($navItems as $nav)
                <a href="{{ route($nav['route']) }}" wire:navigate @click="mobileOpen = false"
                    class="flex items-center gap-2.5 px-3 py-3 rounded-xl text-sm font-medium transition-all
                    {{ request()->routeIs($nav['route'])
                        ? 'bg-orange-50 text-orange-600 font-semibold'
                        : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs($nav['route']) ? 'text-orange-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $nav['icon'] }}"></path>
                    </svg>
                    {{ $nav['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Logout --}}
        <button wire:click="logout"
            class="flex items-center gap-2.5 w-full px-3 py-3 text-sm text-red-600 hover:bg-red-50 rounded-xl transition font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Keluar dari Akun
        </button>
    </div>
</nav>
