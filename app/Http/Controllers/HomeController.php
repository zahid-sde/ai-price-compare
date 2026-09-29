<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Feature;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $countryId = session('user_country_id');
        $activeCountry = $countryId ? Country::find($countryId) : null;

        if (! $activeCountry) {
            $activeCountry = Country::defaultCountry();
        }

        session([
            'user_country_id' => $activeCountry->id,
            'user_country' => $activeCountry,
        ]);

        $products = Product::where('status', 'active')
            ->with(['plans.prices' => function ($q) use ($activeCountry) {
                $q->where('country_id', $activeCountry->id);
            }, 'features'])
            ->get();

        $features = Feature::all();

        return view('public.home', compact('products', 'activeCountry', 'features'));
    }
}
