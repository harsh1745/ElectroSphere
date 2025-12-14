<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Totals
        $userCount = User::count();
        $productCount = Product::count();
        $categoryCount = Category::count();
        $orderCount = Order::count();
        $totalRevenue = (float) Order::sum('total_amount');

        // Months labels (Jan..Dec)
        $months = collect(range(1, 12))->map(function ($m) {
            return Carbon::createFromDate(null, $m, 1)->format('M');
        })->toArray();

        $currentYear = now()->year;

        // Orders grouped by month for the current year
        $ordersByMonthRaw = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Revenue grouped by month for the current year
        $revenueByMonthRaw = Order::selectRaw('MONTH(created_at) as month, COALESCE(SUM(total_amount),0) as revenue')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        // Normalize months - fill 0 if missing
        $ordersByMonth = [];
        $revenueByMonth = [];
        foreach (range(1, 12) as $m) {
            $ordersByMonth[] = isset($ordersByMonthRaw[$m]) ? (int)$ordersByMonthRaw[$m] : 0;
            $revenueByMonth[] = isset($revenueByMonthRaw[$m]) ? (float)$revenueByMonthRaw[$m] : 0.0;
        }

        // Recent orders (latest 5) - eager load user
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Orders by status (for small pie or stats)
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('admin.dashboard', [
            'userCount' => $userCount,
            'productCount' => $productCount,
            'categoryCount' => $categoryCount,
            'orderCount' => $orderCount,
            'totalRevenue' => $totalRevenue,
            'months' => $months,
            'ordersByMonth' => $ordersByMonth,
            'revenueByMonth' => $revenueByMonth,
            'recentOrders' => $recentOrders,
            'ordersByStatus' => $ordersByStatus,
            'currentYear' => $currentYear,
        ]);
    }
}
