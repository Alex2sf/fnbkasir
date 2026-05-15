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
        $user = auth()->user();

        $query = Order::query()->where('status', 'completed');
        
        // Kasir hanya melihat transaksi miliknya sendiri
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        // 1. Basic Stats
        $todayRevenue = (clone $query)->whereDate('created_at', $today)->sum('total_amount');
        $monthRevenue = (clone $query)->where('created_at', '>=', $thisMonth)->sum('total_amount');
        $todayOrdersCount = (clone $query)->whereDate('created_at', $today)->count();

        // 2. Chart Data (Last 7 Days Revenue)
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $revenue = (clone $query)->whereDate('created_at', $date)->sum('total_amount');
            $chartData[] = $revenue;
        }

        // 3. Top Selling Products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function($q) use ($user) {
                $q->where('status', 'completed');
                if (!$user->is_admin) {
                    $q->where('user_id', $user->id);
                }
            })
            ->with('product.category')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 4. Performance per Cashier (Only for Admin)
        $cashierStats = [];
        if ($user->is_admin) {
            $cashierStats = User::where('is_admin', false)
                ->withCount(['orders' => function($q) use ($today) {
                    $q->whereDate('created_at', $today)->where('status', 'completed');
                }])
                ->withSum(['orders as today_revenue' => function($q) use ($today) {
                    $q->whereDate('created_at', $today)->where('status', 'completed');
                }], 'total_amount')
                ->get();
        }

        return view('livewire.dashboard-stats', [
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'todayOrdersCount' => $todayOrdersCount,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
            'topProducts' => $topProducts,
            'cashierStats' => $cashierStats,
        ]);
    }
}
