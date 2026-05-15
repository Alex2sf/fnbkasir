<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

use App\Models\Setting;

class PosApiController extends Controller
{
    public function getSettings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        // Add full URL for logo
        if(isset($settings['store_logo']) && $settings['store_logo']) {
            $settings['store_logo_url'] = asset('storage/' . $settings['store_logo']);
        } else {
            $settings['store_logo_url'] = asset('logo.png');
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function getCategories()
    {
        $categories = Category::all();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function getProducts(Request $request)
    {
        $query = Product::with('category')->where('is_available', true);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Cek ketersediaan stok terlebih dahulu
            foreach ($request->items as $item) {
                $product = Product::with('recipes.ingredient')->lockForUpdate()->find($item['product_id']);
                
                if (!$product) {
                    return response()->json([
                        'success' => false,
                        'message' => "Produk tidak ditemukan.",
                    ], 404);
                }

                if ($product->recipes->isNotEmpty()) {
                    foreach ($product->recipes as $recipe) {
                        if ($recipe->ingredient && $recipe->ingredient->stock < ($recipe->quantity_needed * $item['quantity'])) {
                            return response()->json([
                                'success' => false,
                                'message' => "Stok bahan baku untuk {$product->name} tidak mencukupi.",
                            ], 400);
                        }
                    }
                } else {
                    if ($product->stock < $item['quantity']) {
                        return response()->json([
                            'success' => false,
                            'message' => "Stok produk {$product->name} tidak mencukupi (sisa: {$product->stock}).",
                        ], 400);
                    }
                }
            }

            // Buat order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => auth()->id(),
                'total_amount' => $request->total_amount,
                'status' => 'pending', // Masuk ke Dapur
                'payment_method' => $request->payment_method,
            ]);

            // Buat order items dan kurangi stok
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                $productModel = Product::with('recipes')->find($item['product_id']);
                if ($productModel->recipes->isNotEmpty()) {
                    foreach ($productModel->recipes as $recipe) {
                        $totalNeeded = $recipe->quantity_needed * $item['quantity'];
                        \App\Models\Ingredient::where('id', $recipe->ingredient_id)->decrement('stock', $totalNeeded);
                    }
                } else {
                    $productModel->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'data' => $order->load('items.product')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
