<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountrySessionController extends Controller
{
    public function switchCountry(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        $country = Country::findOrFail($validated['country_id']);
        session([
            'user_country_id' => $country->id,
            'user_country' => $country,
        ]);

        return back()->with('success', "Target country switched to {$country->name} ({$country->currency_code}).");
    }
}
