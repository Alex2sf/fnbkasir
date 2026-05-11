<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStats extends Component
{
    public function render()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // 1. Basic Stats
        $todayRevenue = Order::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount');
        $monthRevenue = Order::where('created_at', '>=', $thisMonth)->where('status', 'completed')->sum('total_amount');
        $todayOrdersCount = Order::whereDate('created_at', $today)->where('status', 'completed')->count();

        // 2. Chart Data (Last 7 Days Revenue)
        $last7Days = collect();
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $revenue = Order::whereDate('created_at', $date)
                            ->where('status', 'completed')
                            ->sum('total_amount');
            $chartData[] = $revenue;
        }

        // 3. Top Selling Products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('livewire.dashboard-stats', [
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'todayOrdersCount' => $todayOrdersCount,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
            'topProducts' => $topProducts,
        ]);
    }
}
