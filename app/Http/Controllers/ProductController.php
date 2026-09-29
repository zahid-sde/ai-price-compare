<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Feature;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $countryId = session('user_country_id', Country::defaultCountry()->id);
        $activeCountry = Country::find($countryId) ?? Country::defaultCountry();

        $query = Product::where('status', 'active')
            ->with(['plans.prices' => function ($q) use ($countryId) {
                $q->where('country_id', $countryId);
            }, 'features']);

        // Filter by feature
        if ($request->filled('feature')) {
            $featureSlug = $request->input('feature');
            $query->whereHas('features', function ($q) use ($featureSlug) {
                $q->where('slug', $featureSlug)->where('is_available', true);
            });
        }

        // Filter by free plan
        if ($request->boolean('free_only')) {
            $query->whereHas('plans.prices', function ($q) use ($countryId) {
                $q->where('country_id', $countryId)->where('price', 0);
            });
        }

        // Filter by max price
        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->input('max_price');
            $query->whereHas('plans.prices', function ($q) use ($countryId, $maxPrice) {
                $q->where('country_id', $countryId)->where('price', '<=', $maxPrice);
            });
        }

        $products = $query->get();
        $features = Feature::all();

        return view('public.tools.index', compact('products', 'activeCountry', 'features'));
    }

    public function show(string $slug)
    {
        $countryId = session('user_country_id', Country::defaultCountry()->id);
        $activeCountry = Country::find($countryId) ?? Country::defaultCountry();

        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with([
                'plans.prices' => function ($q) use ($countryId) {
                    $q->where('country_id', $countryId);
                },
                'features',
                'externalLinks' => function ($q) {
                    $q->where('status', 'active');
                },
                'priceHistories' => function ($q) use ($countryId) {
                    $q->where('country_id', $countryId)->oldest('recorded_at');
                },
            ])
            ->firstOrFail();

        $officialLink = $product->externalLinks->where('type', 'official')->first()
            ?: (object) ['id' => 0, 'url' => $product->official_url];

        $latestPriceRecord = $product->prices()->where('country_id', $activeCountry->id)->first();
        $lastVerifiedDate = $latestPriceRecord?->verified_at?->format('F j, Y') ?? 'September 29, 2026';

        // Prepare Chart.js dataset
        $chartDatasets = [];
        $colors = ['#38bdf8', '#818cf8', '#a855f7', '#34d399', '#fbbf24'];
        $colorIdx = 0;

        foreach ($product->plans as $plan) {
            $histories = $product->priceHistories->where('plan_id', $plan->id);
            $dataPoints = [];

            foreach ($histories as $h) {
                $dataPoints[] = [
                    'x' => $h->verified_at ? $h->verified_at->format('Y-m-d') : $h->recorded_at->format('Y-m-d'),
                    'y' => (float) $h->price,
                ];
            }

            // Always add current price point
            $currentPrice = $plan->prices->firstWhere('country_id', $activeCountry->id);
            if ($currentPrice) {
                $dataPoints[] = [
                    'x' => $currentPrice->verified_at ? $currentPrice->verified_at->format('Y-m-d') : date('Y-m-d'),
                    'y' => (float) $currentPrice->price,
                ];
            }

            if (! empty($dataPoints)) {
                $chartDatasets[] = [
                    'label' => $plan->name.' ('.$activeCountry->currency_symbol.')',
                    'borderColor' => $colors[$colorIdx % count($colors)],
                    'backgroundColor' => $colors[$colorIdx % count($colors)].'20',
                    'data' => $dataPoints,
                    'fill' => true,
                    'tension' => 0.3,
                ];
                $colorIdx++;
            }
        }

        return view('public.tools.show', compact('product', 'activeCountry', 'officialLink', 'lastVerifiedDate', 'chartDatasets'));
    }
}
