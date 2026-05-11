<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PosTerminal extends Component
{
    public $activeCategory = null;
    public $cart = [];
    public $search = '';

    public function mount()
    {
        $firstCategory = Category::first();
        if ($firstCategory) {
            $this->activeCategory = $firstCategory->id;
        }
    }

    public function setCategory($id)
    {
        $this->activeCategory = $id;
    }

    public function addToCart($productId)
    {
        $product = Product::with('recipes.ingredient')->find($productId);
        if (!$product) return;

        $existingKey = array_search($productId, array_column($this->cart, 'id'));
        $currentQty = $existingKey !== false ? $this->cart[$existingKey]['quantity'] : 0;
        $nextQty = $currentQty + 1;

        // Validasi stok
        $hasEnoughStock = true;
        if ($product->recipes->isNotEmpty()) {
            foreach ($product->recipes as $recipe) {
                if ($recipe->ingredient && $recipe->ingredient->stock < ($recipe->quantity_needed * $nextQty)) {
                    $hasEnoughStock = false;
                    session()->flash('error', 'Bahan baku untuk ' . $product->name . ' tidak mencukupi!');
                    break;
                }
            }
        } else {
            if ($product->stock < $nextQty) {
                $hasEnoughStock = false;
                session()->flash('error', 'Stok produk ' . $product->name . ' tidak mencukupi!');
            }
        }

        if (!$hasEnoughStock) return;

        if ($existingKey !== false) {
            $this->cart[$existingKey]['quantity']++;
        } else {
            $this->cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1,
            ];
        }
    }

    public function increaseQuantity($index)
    {
        $productId = $this->cart[$index]['id'];
        $product = Product::with('recipes.ingredient')->find($productId);
        if (!$product) return;

        $nextQty = $this->cart[$index]['quantity'] + 1;

        $hasEnoughStock = true;
        if ($product->recipes->isNotEmpty()) {
            foreach ($product->recipes as $recipe) {
                if ($recipe->ingredient && $recipe->ingredient->stock < ($recipe->quantity_needed * $nextQty)) {
                    $hasEnoughStock = false;
                    session()->flash('error', 'Bahan baku tidak mencukupi!');
                    break;
                }
            }
        } else {
            if ($product->stock < $nextQty) {
                $hasEnoughStock = false;
                session()->flash('error', 'Stok maksimum tercapai!');
            }
        }

        if ($hasEnoughStock) {
            $this->cart[$index]['quantity']++;
        }
    }

    public function decreaseQuantity($index)
    {
        if ($this->cart[$index]['quantity'] > 1) {
            $this->cart[$index]['quantity']--;
        } else {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart); // Re-index
        }
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function getTotalProperty()
    {
        return array_reduce($this->cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function checkout()
    {
        if (empty($this->cart)) return;

        DB::transaction(function () use (&$order) {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => auth()->id(),
                'total_amount' => $this->total,
                'status' => 'pending', // Ubah status menjadi pending agar masuk ke Dapur
                'payment_method' => 'cash',
            ]);

            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Kurangi stok produk atau bahan baku
                $product = Product::with('recipes')->find($item['id']);
                if ($product) {
                    if ($product->recipes->isNotEmpty()) {
                        foreach ($product->recipes as $recipe) {
                            $totalNeeded = $recipe->quantity_needed * $item['quantity'];
                            \App\Models\Ingredient::where('id', $recipe->ingredient_id)->decrement('stock', $totalNeeded);
                        }
                    } else {
                        $product->decrement('stock', $item['quantity']);
                    }
                }
            }
        });

        $this->cart = [];
        session()->flash('message', 'Transaksi berhasil disimpan!');
    }

    public function render()
    {
        $categories = Category::all();
        $products = Product::when($this->activeCategory, function ($query) {
                $query->where('category_id', $this->activeCategory);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get();

        return view('livewire.pos-terminal', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
