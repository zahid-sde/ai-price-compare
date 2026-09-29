<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Click;
use App\Models\Country;
use App\Models\Plan;
use App\Models\Price;
use App\Models\Product;
use App\Models\Search;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalPlans = Plan::count();
        $totalCountries = Country::count();
        $totalPrices = Price::count();
        $totalSearches = Search::count();
        $totalClicks = Click::count();

        $recentSearches = Search::latest()->take(5)->get();
        $recentClicks = Click::with(['product', 'country'])->latest()->take(5)->get();

        $clicksByProduct = Click::select('product_id', DB::raw('count(*) as count'))
            ->groupBy('product_id')
            ->with('product')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalPlans',
            'totalCountries',
            'totalPrices',
            'totalSearches',
            'totalClicks',
            'recentSearches',
            'recentClicks',
            'clicksByProduct'
        ));
    }
}
