<div class="p-4 sm:p-6 space-y-4 sm:space-y-5" style="font-family: 'Inter', sans-serif;">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900">Manajemen Produk</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua produk yang tersedia di menu</p>
        </div>
        <button wire:click="create"
            class="flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 active:scale-95 shadow-md shadow-orange-200 text-sm w-full sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="flex items-center gap-2 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/70 transition-colors duration-150">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&color=f97316&background=fff7ed&size=80' }}"
                                    class="h-11 w-11 rounded-xl object-cover flex-shrink-0 border border-gray-100">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $product->name }}</p>
                                    @if($product->description)<p class="text-xs text-gray-400 truncate max-w-[180px]">{{ $product->description }}</p>@endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">{{ $product->category->name ?? '-' }}</span></td>
                        <td class="px-5 py-4"><span class="text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span></td>
                        <td class="px-5 py-4">
                            <span class="text-sm font-bold {{ $product->stock > 10 ? 'text-emerald-600' : ($product->stock > 0 ? 'text-amber-600' : 'text-red-500') }}">{{ $product->stock }}</span>
                            <span class="text-xs text-gray-400 ml-1">unit</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($product->is_available)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Tersedia</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600"><span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Habis</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $product->id }})" class="px-3 py-1.5 bg-gray-50 hover:bg-orange-50 text-gray-600 hover:text-orange-600 border border-gray-200 hover:border-orange-200 text-xs font-semibold rounded-lg transition-all">Edit</button>
                                <button wire:click="delete({{ $product->id }})" onclick="confirm('Yakin hapus produk ini?') || event.stopImmediatePropagation()" class="px-3 py-1.5 bg-gray-50 hover:bg-red-50 text-gray-600 hover:text-red-600 border border-gray-200 hover:border-red-200 text-xs font-semibold rounded-lg transition-all">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-16 text-center"><p class="text-sm font-medium text-gray-400">Belum ada produk.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())<div class="px-5 py-3 border-t border-gray-100">{{ $products->links() }}</div>@endif
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden space-y-3">
        @forelse($products as $product)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-start gap-3">
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&color=f97316&background=fff7ed&size=80' }}"
                        class="h-14 w-14 rounded-xl object-cover flex-shrink-0 border border-gray-100">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-sm font-bold text-gray-900 leading-tight">{{ $product->name }}</p>
                            @if($product->is_available)
                                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Tersedia</span>
                            @else
                                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-600"><span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Habis</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">{{ $product->category->name ?? '-' }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-sm font-black text-orange-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-400">Stok: <span class="font-semibold {{ $product->stock > 10 ? 'text-emerald-600' : ($product->stock > 0 ? 'text-amber-600' : 'text-red-500') }}">{{ $product->stock }}</span></span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                    <button wire:click="edit({{ $product->id }})" class="flex-1 py-2.5 bg-gray-50 hover:bg-orange-50 text-gray-700 hover:text-orange-600 border border-gray-200 hover:border-orange-200 text-sm font-semibold rounded-xl transition-all text-center">Edit</button>
                    <button wire:click="delete({{ $product->id }})" onclick="confirm('Yakin hapus produk ini?') || event.stopImmediatePropagation()" class="flex-1 py-2.5 bg-gray-50 hover:bg-red-50 text-gray-700 hover:text-red-600 border border-gray-200 hover:border-red-200 text-sm font-semibold rounded-xl transition-all text-center">Hapus</button>
                </div>
            </div>
        @empty
            <div class="text-center py-16"><p class="text-sm font-medium text-gray-400">Belum ada produk.</p></div>
        @endforelse
        @if($products->hasPages())<div class="pt-2">{{ $products->links() }}</div>@endif
    </div>

    {{-- Modal Form --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative bg-white w-full sm:max-w-lg sm:rounded-2xl rounded-t-3xl shadow-2xl border border-gray-100 overflow-hidden">
            {{-- Handle mobile --}}
            <div class="flex justify-center pt-3 sm:hidden"><div class="w-10 h-1 bg-gray-200 rounded-full"></div></div>
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">{{ $product_id ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
                <button wire:click="closeModal" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form wire:submit.prevent="store">
                <div class="px-6 py-5 space-y-4 max-h-[65vh] overflow-y-auto">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Produk</label>
                        <input type="text" wire:model="name" placeholder="Contoh: Nasi Goreng Spesial" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori</label>
                        <select wire:model="category_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp)</label>
                            <input type="number" wire:model="price" placeholder="0" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                            @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok Awal</label>
                            <input type="number" wire:model="stock" placeholder="0" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                            @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea wire:model="description" rows="2" placeholder="Deskripsi singkat..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Gambar Produk</label>
                        @if ($newImage)
                            <div class="mb-2 flex items-center gap-2"><img src="{{ $newImage->temporaryUrl() }}" class="h-14 w-14 object-cover rounded-xl border border-gray-200"><span class="text-xs text-gray-500">Preview gambar baru</span></div>
                        @elseif($image)
                            <div class="mb-2 flex items-center gap-2"><img src="{{ asset('storage/'.$image) }}" class="h-14 w-14 object-cover rounded-xl border border-gray-200"><span class="text-xs text-gray-500">Gambar saat ini</span></div>
                        @endif
                        <input type="file" wire:model="newImage" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                        <p class="text-xs text-gray-400 mt-1.5">Format: JPG, PNG, WEBP. Maksimal 2MB.</p>
                        @error('newImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" wire:model="is_available" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                        <span class="text-sm font-medium text-gray-700">Tersedia untuk dijual</span>
                    </label>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <button type="button" wire:click="closeModal" class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-50 transition active:scale-95">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl transition-all active:scale-95 shadow-md shadow-orange-200">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
