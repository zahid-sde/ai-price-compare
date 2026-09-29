<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ExternalLink;
use App\Models\Product;
use Illuminate\Http\Request;

class LinkAdminController extends Controller
{
    public function index()
    {
        $links = ExternalLink::with(['product', 'country'])->latest()->paginate(20);
        $products = Product::orderBy('name')->get();
        $countries = Country::where('is_active', true)->get();

        return view('admin.links.index', compact('links', 'products', 'countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'country_id' => 'nullable|exists:countries,id',
            'url' => 'required|url',
            'type' => 'required|in:official,affiliate',
            'status' => 'required|in:active,inactive',
        ]);

        ExternalLink::create($validated);

        return redirect()->route('admin.links.index')->with('success', 'External link added successfully.');
    }

    public function destroy(ExternalLink $link)
    {
        $link->delete();

        return redirect()->route('admin.links.index')->with('success', 'Link removed successfully.');
    }
}
