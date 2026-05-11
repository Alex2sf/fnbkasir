<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class StoreSettings extends Component
{
    use WithFileUploads;

    public $store_name;
    public $store_address;
    public $receipt_footer;
    public $logo;
    public $existing_logo;

    public function mount()
    {
        $this->store_name = Setting::get('store_name', 'Warunggalih POS');
        $this->store_address = Setting::get('store_address', '');
        $this->receipt_footer = Setting::get('receipt_footer', 'Terima kasih atas kunjungan Anda!');
        $this->existing_logo = Setting::get('store_logo');
    }

    public function save()
    {
        $this->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'receipt_footer' => 'nullable|string',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        Setting::set('store_name', $this->store_name);
        Setting::set('store_address', $this->store_address);
        Setting::set('receipt_footer', $this->receipt_footer);

        if ($this->logo) {
            if ($this->existing_logo) {
                Storage::disk('public')->delete($this->existing_logo);
            }
            $path = $this->logo->store('settings', 'public');
            Setting::set('store_logo', $path);
            $this->existing_logo = $path;
            $this->logo = null;
        }

        session()->flash('message', 'Pengaturan toko berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.store-settings');
    }
}
