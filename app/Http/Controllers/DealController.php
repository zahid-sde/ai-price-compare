<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Deal;

class DealController extends Controller
{
    public function index()
    {
        $countryId = session('user_country_id', Country::defaultCountry()->id);
        $activeCountry = Country::find($countryId) ?? Country::defaultCountry();

        $deals = Deal::where('status', 'active')
            ->with('product')
            ->latest('verified_at')
            ->get();

        return view('public.deals.index', compact('deals', 'activeCountry'));
    }
}
