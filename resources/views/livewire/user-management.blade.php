<?php

use Livewire\Volt\Component;
use App\Models\User;

new class extends Component {
    public function with(): array
    {
        return [
            'users' => User::latest()->get()
        ];
    }

    public function deleteUser($id)
    {
        if(auth()->id() == $id) {
            $this->js("alert('Anda tidak bisa menghapus akun sendiri!')");
            return;
        }
        
        $user = User::find($id);
        if($user) {
            $user->delete();
            $this->js("alert('Pengguna beserta semua data transaksinya berhasil dihapus!')");
        }
    }
};
?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Pengguna</h2>
            <p class="text-sm text-gray-500">Kelola akun dan akses kasir.</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-sm font-semibold text-gray-700">
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-medium text-gray-900">
                        {{ $user->name }}
                        @if(auth()->id() === $user->id)
                            <span class="text-xs text-orange-500 ml-1">(Kamu)</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-500">{{ $user->email }}</td>
                    <td class="py-3 px-4">
                        @if($user->is_admin)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Admin</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Kasir</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-right">
                        @if(auth()->id() !== $user->id)
                            <button 
                                wire:click="deleteUser({{ $user->id }})"
                                wire:confirm="Yakin ingin menghapus pengguna ini?\n\nPERHATIAN: Semua data transaksi (orders & items) yang pernah dibuat oleh pengguna ini akan TERHAPUS PERMANEN!"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
