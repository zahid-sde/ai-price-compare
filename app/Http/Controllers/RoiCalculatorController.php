<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\Request;

class RoiCalculatorController extends Controller
{
    public function index(Request $request)
    {
        $countryId = session('user_country_id', Country::defaultCountry()->id);
        $activeCountry = Country::find($countryId) ?? Country::defaultCountry();

        $codingHours = (float) $request->input('coding_hours', 2);
        $researchQueries = (int) $request->input('research_queries', 15);
        $fileUploads = (int) $request->input('file_uploads', 5);
        $needsImages = $request->boolean('needs_images', true);
        $budgetLimit = (float) $request->input('budget', 20);

        // Fetch products and prices
        $products = Product::where('status', 'active')
            ->with(['plans.prices' => function ($q) use ($countryId) {
                $q->where('country_id', $countryId);
            }, 'features'])
            ->get();

        $calculatedResults = [];

        foreach ($products as $product) {
            $pricingInfo = $product->startingPriceForCountry($activeCountry);

            // Calculate estimated value score based on user usage
            $hasCoding = $product->features->firstWhere('slug', 'coding')?->pivot->is_available ?? false;
            $hasResearch = $product->features->firstWhere('slug', 'research')?->pivot->is_available ?? false;
            $hasFiles = $product->features->firstWhere('slug', 'file-upload')?->pivot->is_available ?? false;
            $hasImages = $product->features->firstWhere('slug', 'image-generation')?->pivot->is_available ?? false;

            $score = 0;
            if ($codingHours > 0 && $hasCoding) {
                $score += min($codingHours * 10, 40);
            }
            if ($researchQueries > 0 && $hasResearch) {
                $score += min($researchQueries * 2, 30);
            }
            if ($fileUploads > 0 && $hasFiles) {
                $score += min($fileUploads * 3, 20);
            }
            if ($needsImages && $hasImages) {
                $score += 10;
            }

            // Estimate cost per working day
            $monthlyPrice = $pricingInfo['raw'] ?? 0;
            $costPerDay = $monthlyPrice > 0 ? round($monthlyPrice / 30, 2) : 0;
            $costPerHourOfUse = ($monthlyPrice > 0 && $codingHours > 0)
                ? round($monthlyPrice / (30 * $codingHours), 2)
                : 0;

            $fitsBudget = $monthlyPrice <= $budgetLimit;

            $calculatedResults[] = [
                'product' => $product,
                'monthly_price' => $monthlyPrice,
                'formatted_price' => $pricingInfo['formatted'],
                'has_free' => $pricingInfo['has_free'],
                'score' => $score,
                'cost_per_day' => $costPerDay,
                'cost_per_hour' => $costPerHourOfUse,
                'fits_budget' => $fitsBudget,
            ];
        }

        // Sort by score descending, then price ascending
        usort($calculatedResults, function ($a, $b) {
            if ($a['score'] === $b['score']) {
                return $a['monthly_price'] <=> $b['monthly_price'];
            }

            return $b['score'] <=> $a['score'];
        });

        return view('public.calculator.index', compact(
            'codingHours',
            'researchQueries',
            'fileUploads',
            'needsImages',
            'budgetLimit',
            'calculatedResults',
            'activeCountry'
        ));
    }
}
