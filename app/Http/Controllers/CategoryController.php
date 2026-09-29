<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Feature;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $countryId = session('user_country_id', Country::defaultCountry()->id);
        $activeCountry = Country::find($countryId) ?? Country::defaultCountry();

        $feature = Feature::where('slug', $slug)->first();
        $isCheapest = $slug === 'cheapest';

        if (! $feature && ! $isCheapest) {
            abort(404);
        }

        $query = Product::where('status', 'active')
            ->with(['plans.prices' => function ($q) use ($countryId) {
                $q->where('country_id', $countryId);
            }, 'features']);

        if ($feature) {
            $query->whereHas('features', function ($q) use ($feature) {
                $q->where('feature_id', $feature->id)->where('is_available', true);
            });
            $title = "Best AI Tools for {$feature->name} ({$activeCountry->name})";
            $description = "Compare verified subscription prices and plans for top AI tools specializing in {$feature->name}.";
        } else {
            $title = "Cheapest & Free AI Tools List ({$activeCountry->name})";
            $description = 'Compare the most affordable and free subscription plans for popular AI products.';
        }

        $products = $query->get();

        // Schema.org ItemList JSON-LD
        $itemListElement = [];
        foreach ($products as $idx => $p) {
            $pInfo = $p->startingPriceForCountry($activeCountry);
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $idx + 1,
                'name' => $p->name,
                'url' => route('products.show', $p->slug),
                'description' => $p->description,
            ];
        }

        $schemaJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => $title,
            'description' => $description,
            'itemListElement' => $itemListElement,
        ];

        return view('public.category.show', compact(
            'feature',
            'isCheapest',
            'title',
            'description',
            'products',
            'activeCountry',
            'schemaJsonLd'
        ));
    }
}
