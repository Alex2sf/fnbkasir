<div class="p-6" style="font-family: 'Inter', sans-serif;">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-gray-900">Pengaturan Toko</h1>
        <p class="text-sm text-gray-500 mt-0.5">Konfigurasi identitas dan branding usaha Anda</p>
    </div>

    <form wire:submit.prevent="save">
        {{-- Flash Success --}}
        @if (session()->has('message'))
            <div class="flex items-center gap-2 px-4 py-3 mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-5 max-w-4xl">

            {{-- Logo Toko --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="grid md:grid-cols-[1fr_2fr]">
                    <div class="px-6 py-5 bg-gray-50 border-r border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900">Logo Toko</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                            Logo akan muncul di navigasi dan struk cetak. Format: JPG, PNG, max 2MB.
                        </p>
                    </div>
                    <div class="px-6 py-5">
                        <div class="flex items-start gap-5">
                            {{-- Preview --}}
                            <div class="flex-shrink-0">
                                @if ($logo)
                                    <img class="h-24 w-24 object-cover rounded-2xl border border-gray-200 shadow-sm" src="{{ $logo->temporaryUrl() }}" alt="Preview">
                                @elseif ($existing_logo)
                                    <img class="h-24 w-24 object-cover rounded-2xl border border-gray-200 shadow-sm" src="{{ asset('storage/' . $existing_logo) }}" alt="Current Logo">
                                @else
                                    <div class="h-24 w-24 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-300">
                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs">Logo</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Upload --}}
                            <div class="flex-1">
                                <label class="flex flex-col items-center justify-center w-full h-24 bg-gray-50 hover:bg-orange-50 border-2 border-dashed border-gray-200 hover:border-orange-300 rounded-2xl cursor-pointer transition-all duration-200 group">
                                    <svg class="w-6 h-6 text-gray-300 group-hover:text-orange-400 mb-1.5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <span class="text-xs font-medium text-gray-400 group-hover:text-orange-500 transition-colors">Klik untuk upload logo</span>
                                    <input type="file" wire:model="logo" class="hidden" accept="image/*">
                                </label>
                                <div wire:loading wire:target="logo" class="flex items-center gap-1.5 text-orange-500 text-xs mt-2 font-medium">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    Mengunggah...
                                </div>
                                @error('logo') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nama Toko --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="grid md:grid-cols-[1fr_2fr]">
                    <div class="px-6 py-5 bg-gray-50 border-r border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900">Nama Toko</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">Tampil di navbar dan header struk pelanggan.</p>
                    </div>
                    <div class="px-6 py-5">
                        <input type="text" id="store_name" wire:model="store_name"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            placeholder="Contoh: Warunggalih Steakhouse">
                        @error('store_name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Alamat Toko --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="grid md:grid-cols-[1fr_2fr]">
                    <div class="px-6 py-5 bg-gray-50 border-r border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900">Alamat Lengkap</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">Ditampilkan di bagian atas struk cetak pelanggan.</p>
                    </div>
                    <div class="px-6 py-5">
                        <textarea id="store_address" wire:model="store_address" rows="3"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none"
                            placeholder="Jl. Mawar No. 12, Jakarta Selatan..."></textarea>
                        @error('store_address') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Pesan Struk --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="grid md:grid-cols-[1fr_2fr]">
                    <div class="px-6 py-5 bg-gray-50 border-r border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900">Pesan Bawah Struk</h3>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">Ucapan terima kasih atau promosi di footer struk.</p>
                    </div>
                    <div class="px-6 py-5">
                        <input type="text" id="receipt_footer" wire:model="receipt_footer"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            placeholder="Terima kasih, selamat menikmati! 🍽️">
                        @error('receipt_footer') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-xl transition-all duration-200 active:scale-95 shadow-lg shadow-orange-200 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
