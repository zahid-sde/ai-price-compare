<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;

class PricingAdminController extends Controller
{
    public function index()
    {
        $prices = Price::with(['product', 'plan', 'country'])->latest('updated_at')->paginate(20);

        return view('admin.prices.index', compact('prices'));
    }

    public function create()
    {
        $products = Product::with('plans')->orderBy('name')->get();
        $countries = Country::where('is_active', true)->get();

        return view('admin.prices.form', compact('products', 'countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'plan_id' => 'required|exists:plans,id',
            'country_id' => 'required|exists:countries,id',
            'currency' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'billing_period' => 'required|in:free,monthly,yearly,one_time',
            'source_url' => 'required|url',
            'verified_at' => 'required|date',
        ]);

        Price::updateOrCreate(
            [
                'product_id' => $validated['product_id'],
                'plan_id' => $validated['plan_id'],
                'country_id' => $validated['country_id'],
            ],
            $validated
        );

        return redirect()->route('admin.prices.index')->with('success', 'Pricing updated and logged in price history.');
    }

    public function edit(Price $price)
    {
        $products = Product::with('plans')->orderBy('name')->get();
        $countries = Country::where('is_active', true)->get();

        return view('admin.prices.form', compact('price', 'products', 'countries'));
    }

    public function update(Request $request, Price $price)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'plan_id' => 'required|exists:plans,id',
            'country_id' => 'required|exists:countries,id',
            'currency' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'billing_period' => 'required|in:free,monthly,yearly,one_time',
            'source_url' => 'required|url',
            'verified_at' => 'required|date',
        ]);

        $price->update($validated);

        return redirect()->route('admin.prices.index')->with('success', 'Pricing updated and price history recorded.');
    }

    public function destroy(Price $price)
    {
        $price->delete();

        return redirect()->route('admin.prices.index')->with('success', 'Price record deleted.');
    }
}
