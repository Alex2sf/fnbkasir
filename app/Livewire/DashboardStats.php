<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardStats extends Component
{
    public function deleteUser($id)
    {
        if(auth()->id() == $id) {
            $this->js("alert('Tidak bisa menghapus akun sendiri!')");
            return;
        }
        
        $user = User::find($id);
        if($user) {
            $user->delete();
            $this->js("alert('Usaha/Kasir berhasil dihapus! Semua transaksinya juga sudah terhapus permanen.')");
        }
    }

    public function render()
    {
        $today      = Carbon::today();
        $thisMonth  = Carbon::now()->startOfMonth();
        $user       = auth()->user();

        $query = Order::query()->where('status', 'completed');
        
        // Kasir hanya melihat transaksi miliknya sendiri
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        // 1. Basic Stats
        $todayRevenue      = (clone $query)->whereDate('created_at', $today)->sum('total_amount');
        $monthRevenue      = (clone $query)->where('created_at', '>=', $thisMonth)->sum('total_amount');
        $todayOrdersCount  = (clone $query)->whereDate('created_at', $today)->count();
        $yesterdayRevenue  = (clone $query)->whereDate('created_at', Carbon::yesterday())->sum('total_amount');
        $revenueChange     = $yesterdayRevenue > 0
            ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100
            : null;

        // 2. Chart Data (Last 7 Days Revenue)
        $chartLabels = [];
        $chartData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date           = Carbon::today()->subDays($i);
            $chartLabels[]  = $date->format('d M');
            $revenue        = (clone $query)->whereDate('created_at', $date)->sum('total_amount');
            $chartData[]    = $revenue;
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
        $cashierStats   = [];
        $pieChartLabels = [];
        $pieChartData   = [];

        if ($user->is_admin) {
            $cashiers = User::where('is_admin', false)
                ->withCount(['orders' => function($q) use ($today) {
                    $q->whereDate('created_at', $today)->where('status', 'completed');
                }])
                ->withSum(['orders as today_revenue' => function($q) use ($today) {
                    $q->whereDate('created_at', $today)->where('status', 'completed');
                }], 'total_amount')
                ->withSum(['orders as month_revenue' => function($q) use ($thisMonth) {
                    $q->where('created_at', '>=', $thisMonth)->where('status', 'completed');
                }], 'total_amount')
                ->get();

            $cashierStats = $cashiers;

            foreach($cashiers as $c) {
                if($c->month_revenue > 0) {
                    $pieChartLabels[] = $c->name;
                    $pieChartData[]   = (float) $c->month_revenue;
                }
            }
        }

        return view('livewire.dashboard-stats', [
            'todayRevenue'      => $todayRevenue,
            'monthRevenue'      => $monthRevenue,
            'todayOrdersCount'  => $todayOrdersCount,
            'revenueChange'     => $revenueChange,
            'chartLabels'       => json_encode($chartLabels),
            'chartData'         => json_encode($chartData),
            'topProducts'       => $topProducts,
            'cashierStats'      => $cashierStats,
            'pieChartLabels'    => json_encode($pieChartLabels),
            'pieChartData'      => json_encode($pieChartData),
        ]);
    }
}
