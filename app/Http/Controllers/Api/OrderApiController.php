<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderApiController extends Controller
{
    // GET /api/orders
    public function getOrders(Request $request)
    {
        $query = Order::with('items.product')->latest();
        
        // Kasir hanya bisa melihat transaksi miliknya sendiri
        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();
        return response()->json(['success' => true, 'data' => $orders]);
    }

    // GET /api/kitchen/orders
    public function getKitchenOrders()
    {
        // Menampilkan order yang belum selesai (pending)
        $orders = Order::with('items.product')
            ->where('status', 'pending')
            ->oldest()
            ->get();
            
        return response()->json(['success' => true, 'data' => $orders]);
    }

    // POST /api/kitchen/orders/{id}/status
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string|in:pending,completed']);
        
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Status pesanan diperbarui', 'data' => $order]);
    }
}
