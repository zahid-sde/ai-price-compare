<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryAdminController extends Controller
{
    public function index()
    {
        $countries = Country::withCount('prices')->get();

        return view('admin.countries.index', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:countries,code',
            'currency_code' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Country::create($validated);

        return redirect()->route('admin.countries.index')->with('success', 'Country added successfully.');
    }
}
