<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Feature;
use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(Request $request, ?string $slugs = null)
    {
        $countryId = session('user_country_id', Country::defaultCountry()->id);
        $activeCountry = Country::find($countryId) ?? Country::defaultCountry();

        $selectedSlugs = [];

        if ($slugs) {
            // e.g. chatgpt-vs-claude-vs-gemini
            $parts = explode('-vs-', $slugs);
            $selectedSlugs = array_map('trim', $parts);
        } elseif ($request->filled('products')) {
            $selectedSlugs = (array) $request->input('products');
        }

        if (empty($selectedSlugs)) {
            // Default 3 products to compare if none selected
            $selectedSlugs = ['chatgpt', 'claude', 'gemini'];
        }

        // Limit to max 4 products for clean layout
        $selectedSlugs = array_slice($selectedSlugs, 0, 4);

        $comparedProducts = Product::whereIn('slug', $selectedSlugs)
            ->where('status', 'active')
            ->with([
                'plans.prices' => function ($q) use ($countryId) {
                    $q->where('country_id', $countryId);
                },
                'features',
            ])
            ->get();

        $allProducts = Product::where('status', 'active')->orderBy('name')->get();
        $allFeatures = Feature::orderBy('category')->orderBy('name')->get();

        // SEO Title & Meta Description Builder
        $productNames = $comparedProducts->pluck('name')->toArray();
        if (count($productNames) >= 2) {
            $seoTitle = implode(' vs ', $productNames)." Comparison ({$activeCountry->name}) - AI Price Compare";
            $seoDescription = 'Side-by-side comparison of '.implode(', ', $productNames)." subscription prices, free plans, features, and official source links in {$activeCountry->name}.";
        } else {
            $seoTitle = 'Compare AI Tools Side-by-Side - AI Price Compare';
            $seoDescription = "Compare AI product subscription pricing and feature availability transparently in {$activeCountry->name}.";
        }

        // Schema.org JSON-LD Structured Data for Comparison
        $schemaJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $seoTitle,
            'description' => $seoDescription,
            'mainEntity' => [
                '@type' => 'ItemList',
                'name' => 'Compared AI Products',
                'itemListElement' => $comparedProducts->map(function ($p, $idx) use ($activeCountry) {
                    $pInfo = $p->startingPriceForCountry($activeCountry);

                    return [
                        '@type' => 'ListItem',
                        'position' => $idx + 1,
                        'name' => $p->name,
                        'url' => route('products.show', $p->slug),
                        'offers' => [
                            '@type' => 'Offer',
                            'price' => $pInfo['raw'] ?? 0,
                            'priceCurrency' => $activeCountry->currency_code,
                            'url' => $pInfo['source_url'],
                        ],
                    ];
                })->values()->toArray(),
            ],
        ];

        return view('public.compare.index', compact(
            'comparedProducts',
            'allProducts',
            'allFeatures',
            'activeCountry',
            'selectedSlugs',
            'seoTitle',
            'seoDescription',
            'schemaJsonLd'
        ));
    }
}
