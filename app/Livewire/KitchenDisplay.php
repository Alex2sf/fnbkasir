<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class KitchenDisplay extends Component
{
    public function render()
    {
        // Ambil order yang statusnya pending, urutkan dari yang terlama (FIFO)
        $pendingOrders = Order::with('items.product')
            ->where('status', 'pending')
            ->oldest()
            ->get();

        return view('livewire.kitchen-display', [
            'orders' => $pendingOrders
        ]);
    }

    public function markAsCompleted($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => 'completed']);
            // Di sistem kasir, ini berarti pesanan sudah selesai dimasak dan siap dihidangkan
        }
    }
}
