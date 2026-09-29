<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\AiFinderService;
use Illuminate\Http\Request;

class AiFinderController extends Controller
{
    public function index(Request $request, AiFinderService $finderService)
    {
        $countryId = session('user_country_id', Country::defaultCountry()->id);
        $activeCountry = Country::find($countryId) ?? Country::defaultCountry();

        $userQuery = $request->input('q', '');
        $searchResults = null;

        if (! empty(trim($userQuery))) {
            $searchResults = $finderService->search($userQuery, $activeCountry, $request->ip());
        }

        return view('public.finder.index', compact('userQuery', 'searchResults', 'activeCountry'));
    }
}
