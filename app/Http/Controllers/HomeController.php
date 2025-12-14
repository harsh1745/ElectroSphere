<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Top ordered products
        $topProducts = Product::select('products.*')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('COUNT(order_items.product_id) as total_sold')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'DESC')
            ->take(8)
            ->get();

        if ($topProducts->count() == 0) {
            $topProducts = Product::inRandomOrder()->take(8)->get();
        }

        $trendingProducts = Product::inRandomOrder()->take(6)->get();
        $latestArrivals = Product::latest()->take(6)->get();
        $recommendedProducts = Product::inRandomOrder()->take(6)->get();

        return view('frontend.Home.home', compact(
            'topProducts',
            'trendingProducts',
            'latestArrivals',
            'recommendedProducts'
        ));
        return view('home');
    }
}
