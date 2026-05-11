<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $isModalOpen = false;
    public $product_id, $name, $description, $price, $stock, $category_id, $image, $is_available = true;
    public $newImage;

    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'is_available' => 'boolean',
        'newImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB Max, JPG/PNG/WEBP
    ];

    public function render()
    {
        return view('livewire.product-manager', [
            'products' => Product::with('category')->latest()->paginate(10),
            'categories' => Category::all(),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->product_id = '';
        $this->name = '';
        $this->category_id = '';
        $this->price = '';
        $this->stock = '';
        $this->description = '';
        $this->image = '';
        $this->newImage = null;
        $this->is_available = true;
    }

    public function store()
    {
        $this->validate();

        $imagePath = $this->image;

        if ($this->newImage) {
            $imagePath = $this->newImage->store('products', 'public');
            // Jika update dan ada gambar lama, bisa dihapus di sini
            if ($this->product_id && $this->image) {
                Storage::disk('public')->delete($this->image);
            }
        }

        Product::updateOrCreate(['id' => $this->product_id], [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,
            'image' => $imagePath,
            'is_available' => $this->is_available,
        ]);

        session()->flash('message', $this->product_id ? 'Produk berhasil diupdate.' : 'Produk berhasil ditambahkan.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->description = $product->description;
        $this->image = $product->image;
        $this->is_available = $product->is_available;
        $this->newImage = null;

        $this->openModal();
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        session()->flash('message', 'Produk berhasil dihapus.');
    }
}
