<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrderHistory extends Component
{
    use WithPagination;

    public $selectedOrder = null;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.order-history', [
            'orders' => Order::with('user')->latest()->paginate(10),
        ]);
    }

    public function viewDetails($orderId)
    {
        $this->selectedOrder = Order::with(['items.product', 'user'])->findOrFail($orderId);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedOrder = null;
    }

    public function printReceipt()
    {
        if ($this->selectedOrder) {
            $this->dispatch('print-receipt');
        }
    }
}
