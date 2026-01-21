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
    $selectedYear = $request->get('year', now()->year);

    // =======================
    // BASIC TOTALS
    // =======================
    $userCount = User::count();
    $productCount = Product::count();
    $categoryCount = Category::count();
    $orderCount = Order::count();

    // 👉 Revenue ab selected year ka
    $totalRevenue = (float) Order::whereYear('created_at', $selectedYear)
        ->sum('total_amount');

    // =======================
    // MONTHLY CHART DATA (SELECTED YEAR)
    // =======================
    $months = collect(range(1, 12))->map(function ($m) {
        return Carbon::createFromDate(null, $m, 1)->format('M');
    })->toArray();

    $ordersByMonthRaw = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    $revenueByMonthRaw = Order::selectRaw('MONTH(created_at) as month, COALESCE(SUM(total_amount),0) as revenue')
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month')
        ->pluck('revenue', 'month')
        ->toArray();

    $ordersByMonth = [];
    $revenueByMonth = [];

    foreach (range(1, 12) as $m) {
        $ordersByMonth[] = $ordersByMonthRaw[$m] ?? 0;
        $revenueByMonth[] = $revenueByMonthRaw[$m] ?? 0;
    }

    // =======================
    // DAILY CHART (CURRENT MONTH of SELECTED YEAR)
    // =======================
    $currentMonth = now()->month;
    $daysInMonth = Carbon::now()->daysInMonth;
    $days = range(1, $daysInMonth);

    $ordersByDayRaw = Order::selectRaw('DAY(created_at) as day, COUNT(*) as total')
        ->whereYear('created_at', $selectedYear)
        ->whereMonth('created_at', $currentMonth)
        ->groupBy('day')
        ->pluck('total', 'day')
        ->toArray();

    $revenueByDayRaw = Order::selectRaw('DAY(created_at) as day, COALESCE(SUM(total_amount),0) as revenue')
        ->whereYear('created_at', $selectedYear)
        ->whereMonth('created_at', $currentMonth)
        ->groupBy('day')
        ->pluck('revenue', 'day')
        ->toArray();

    $ordersByDay = [];
    $revenueByDay = [];

    foreach ($days as $d) {
        $ordersByDay[] = $ordersByDayRaw[$d] ?? 0;
        $revenueByDay[] = $revenueByDayRaw[$d] ?? 0;
    }

    // =======================
    // SIDE DATA
    // =======================
    $recentOrders = Order::with('user')->latest()->take(5)->get();

    $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    return view('admin.dashboard', compact(
        'userCount',
        'productCount',
        'categoryCount',
        'orderCount',
        'totalRevenue',

        // Monthly
        'months',
        'ordersByMonth',
        'revenueByMonth',
        'selectedYear',

        // Daily
        'days',
        'ordersByDay',
        'revenueByDay',

        // Side
        'recentOrders',
        'ordersByStatus'
    ));
}

}
