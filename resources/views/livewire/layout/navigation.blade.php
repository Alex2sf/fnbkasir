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
    $storeName = \App\Models\Setting::get('store_name', 'Warunggalih');
    $navItems = [];

    if (auth()->check()) {
        if (auth()->user()->is_admin) {
            $navItems = [
                ['group' => 'Utama', 'items' => [
                    ['route' => 'dashboard', 'label' => 'Dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'pos',       'label' => 'Mesin Kasir', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M12 7h.01M15 7h.01M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-4M9 21v-4a2 2 0 012-2h2a2 2 0 012 2v4'],
                ]],
                ['group' => 'Manajemen Data', 'items' => [
                    ['route' => 'products',   'label' => 'Data Produk',   'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4'],
                    ['route' => 'categories', 'label' => 'Kategori Menu', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                    ['route' => 'users',      'label' => 'Data Kasir',    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ]],
                ['group' => 'Operasional', 'items' => [
                    ['route' => 'orders',  'label' => 'Riwayat Order', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                    ['route' => 'kitchen', 'label' => 'Display Dapur', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ]],
                ['group' => 'Sistem', 'items' => [
                    ['route' => 'settings',   'label' => 'Pengaturan Toko', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ]],
            ];
        } else {
            $navItems = [
                ['group' => 'Utama', 'items' => [
                    ['route' => 'dashboard', 'label' => 'Dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'pos',       'label' => 'Mesin Kasir', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M12 7h.01M15 7h.01M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-4M9 21v-4a2 2 0 012-2h2a2 2 0 012 2v4'],
                ]],
                ['group' => 'Manajemen Data', 'items' => [
                    ['route' => 'products',   'label' => 'Data Produk',   'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4'],
                    ['route' => 'categories', 'label' => 'Kategori Menu', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ]],
                ['group' => 'Operasional', 'items' => [
                    ['route' => 'orders',  'label' => 'Riwayat Order', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                    ['route' => 'kitchen', 'label' => 'Display Dapur', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ]],
                ['group' => 'Sistem', 'items' => [
                    ['route' => 'settings',   'label' => 'Pengaturan Toko', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ]],
            ];
        }
    }
@endphp

<div class="flex flex-col h-full py-5 overflow-y-auto scrollbar-hide">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 mb-6 flex-shrink-0">
        @if($storeLogo)
            <img src="{{ asset('storage/' . $storeLogo) }}" alt="{{ $storeName }}" class="h-9 w-9 object-cover rounded-xl ring-2 ring-orange-500/30">
        @else
            <img src="{{ asset('logo.png') }}" alt="{{ $storeName }}" class="h-9 w-9 object-cover rounded-xl ring-2 ring-orange-500/30">
        @endif
        <div>
            <p class="font-black text-white text-sm leading-tight">{{ $storeName }}</p>
            <p class="text-[10px] text-orange-400/70 font-medium">Management System</p>
        </div>
    </div>

    {{-- Divider --}}
    <div class="mx-5 mb-4 border-t border-white/5"></div>

    {{-- Nav Groups --}}
    <nav class="flex-1 space-y-5 px-1.5">
        @foreach($navItems as $group)
            <div>
                <p class="text-[10px] font-bold text-orange-500/50 uppercase tracking-widest px-3 mb-1.5">{{ $group['group'] }}</p>
                @foreach($group['items'] as $nav)
                    <a href="{{ route($nav['route']) }}" wire:navigate
                        class="sidebar-item {{ request()->routeIs($nav['route']) ? 'active' : '' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $nav['icon'] }}"/>
                        </svg>
                        <span>{{ $nav['label'] }}</span>
                        @if(request()->routeIs($nav['route']))
                            <div class="ml-auto w-1.5 h-1.5 bg-white/60 rounded-full"></div>
                        @endif
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    {{-- Bottom: User Profile + Logout --}}
    <div class="mt-auto pt-4 border-t border-white/5 mx-3">
        {{-- User Info --}}
        <div class="flex items-center gap-3 px-3 py-3 mb-2 rounded-xl bg-white/5">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xs font-black flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-white/90 truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-white/40 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        {{-- Profile & Logout --}}
        <a href="{{ route('profile') }}" wire:navigate
            class="sidebar-item text-xs">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil Saya
        </a>
        <button wire:click="logout"
            class="sidebar-item w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10 text-xs">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Keluar
        </button>
    </div>
</div>
