<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function getStats(Request $request)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $user = $request->user();

        $query = Order::query()->where('status', 'completed');
        
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        $todayRevenue = (clone $query)->whereDate('created_at', $today)->sum('total_amount');
        $monthRevenue = (clone $query)->where('created_at', '>=', $thisMonth)->sum('total_amount');
        $todayOrdersCount = (clone $query)->whereDate('created_at', $today)->count();

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $revenue = (clone $query)->whereDate('created_at', $date)->sum('total_amount');
            $chartData[] = $revenue;
        }

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

        $response = [
            'today_revenue' => $todayRevenue,
            'month_revenue' => $monthRevenue,
            'today_orders_count' => $todayOrdersCount,
            'chart' => [
                'labels' => $chartLabels,
                'data' => $chartData,
            ],
            'top_products' => $topProducts,
        ];

        if ($user->is_admin) {
            $cashierStats = User::where('is_admin', false)
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

            $pieChartLabels = [];
            $pieChartData = [];
            
            foreach($cashierStats as $c) {
                if($c->month_revenue > 0) {
                    $pieChartLabels[] = $c->name;
                    $pieChartData[] = (float) $c->month_revenue;
                }
            }

            $response['admin_stats'] = [
                'cashier_performance' => $cashierStats,
                'pie_chart' => [
                    'labels' => $pieChartLabels,
                    'data' => $pieChartData
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }
}
