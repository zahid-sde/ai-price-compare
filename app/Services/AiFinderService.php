<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Product;
use App\Models\Search;
use Illuminate\Support\Str;

class AiFinderService
{
    /**
     * Search and match products based on natural language input.
     */
    public function search(string $userQuery, Country $country, ?string $ipAddress = null): array
    {
        $queryLower = strtolower($userQuery);

        // 1. Extract Budget
        $maxBudget = $this->extractBudget($queryLower);
        $wantsFreeOnly = str_contains($queryLower, 'free') && $maxBudget === null;

        // 2. Extract Use Case Requirements
        $requiredFeatureSlugs = $this->extractFeatures($queryLower);

        // 3. Query Database for Products
        $products = Product::where('status', 'active')
            ->with(['plans.prices' => function ($q) use ($country) {
                $q->where('country_id', $country->id);
            }, 'features'])
            ->get();

        $matchedResults = [];

        foreach ($products as $product) {
            $pricingInfo = $product->startingPriceForCountry($country);
            $plansForCountry = $product->plans->map(function ($plan) use ($country) {
                $price = $plan->prices->firstWhere('country_id', $country->id);

                return [
                    'plan_name' => $plan->name,
                    'price' => $price ? $price->price : null,
                    'formatted_price' => $price ? $price->formatted_price : 'N/A',
                    'billing_period' => $price ? $price->billing_period : 'N/A',
                    'verified_at' => $price?->verified_at?->format('F j, Y'),
                    'source_url' => $price?->source_url ?: $product->official_url,
                ];
            })->filter(fn ($p) => $p['price'] !== null);

            // Check budget constraint
            $suitablePlans = $plansForCountry->filter(function ($p) use ($maxBudget, $wantsFreeOnly) {
                if ($wantsFreeOnly) {
                    return $p['price'] == 0;
                }
                if ($maxBudget !== null) {
                    return $p['price'] <= $maxBudget;
                }

                return true;
            });

            if ($suitablePlans->isEmpty() && ($maxBudget !== null || $wantsFreeOnly)) {
                continue; // Skip product if no plan fits budget
            }

            // Check feature constraints
            $productFeatures = $product->features->keyBy('slug');
            $matchedFeaturesCount = 0;
            $featureBreakdown = [];

            foreach ($requiredFeatureSlugs as $fSlug) {
                $featureObj = $productFeatures->get($fSlug);
                $isAvailable = $featureObj && $featureObj->pivot->is_available;
                if ($isAvailable) {
                    $matchedFeaturesCount++;
                }
                $featureBreakdown[$fSlug] = [
                    'name' => $featureObj ? $featureObj->name : Str::title(str_replace('-', ' ', $fSlug)),
                    'available' => (bool) $isAvailable,
                ];
            }

            // If features were explicitly requested, ensure product meets at least part or all
            $matchPercentage = count($requiredFeatureSlugs) > 0
                ? round(($matchedFeaturesCount / count($requiredFeatureSlugs)) * 100)
                : 100;

            // Generate factual explanation strictly from database values
            $bestPlan = $suitablePlans->first() ?? $plansForCountry->first();
            $explanation = $this->generateExplanation($product, $bestPlan, $featureBreakdown, $country);

            $matchedResults[] = [
                'product' => $product,
                'starting_price' => $pricingInfo,
                'best_fitting_plan' => $bestPlan,
                'all_plans' => $plansForCountry->values()->toArray(),
                'feature_breakdown' => $featureBreakdown,
                'match_percentage' => $matchPercentage,
                'explanation' => $explanation,
            ];
        }

        // Sort by match percentage descending, then price ascending
        usort($matchedResults, function ($a, $b) {
            if ($a['match_percentage'] === $b['match_percentage']) {
                $priceA = $a['best_fitting_plan']['price'] ?? 9999;
                $priceB = $b['best_fitting_plan']['price'] ?? 9999;

                return $priceA <=> $priceB;
            }

            return $b['match_percentage'] <=> $a['match_percentage'];
        });

        $intentSummary = [
            'budget' => $wantsFreeOnly ? 'Free ($0)' : ($maxBudget !== null ? "Up to {$country->currency_symbol}{$maxBudget}/month" : 'Any budget'),
            'country' => $country->name." ({$country->currency_code})",
            'required_features' => array_map(fn ($slug) => Str::title(str_replace('-', ' ', $slug)), $requiredFeatureSlugs),
        ];

        // Save Search log
        Search::create([
            'query' => $userQuery,
            'parsed_intent' => $intentSummary,
            'results_count' => count($matchedResults),
            'ip_address' => $ipAddress,
        ]);

        return [
            'query' => $userQuery,
            'intent' => $intentSummary,
            'results' => $matchedResults,
        ];
    }

    /**
     * Parse budget from natural language string.
     */
    private function extractBudget(string $query): ?float
    {
        // e.g. "under $20", "under 20", "less than $30", "$20/month", "20/mo", "budget 25"
        if (preg_match('/(?:under|less than|max|budget(?: of)?|around|\$)\s*\$?(\d+(?:\.\d{1,2})?)/i', $query, $matches)) {
            return (float) $matches[1];
        }

        if (preg_match('/(\d+(?:\.\d{1,2})?)\s*(?:\/|\s*per\s*)month/i', $query, $matches)) {
            return (float) $matches[1];
        }

        return null;
    }

    /**
     * Parse requested feature keywords from input.
     */
    private function extractFeatures(string $query): array
    {
        $features = [];

        if (preg_match('/coding|developer|code|program|software|python|javascript/i', $query)) {
            $features[] = 'coding';
        }
        if (preg_match('/research|search|academic|sources|citations|web/i', $query)) {
            $features[] = 'research';
        }
        if (preg_match('/writing|writer|copywriting|essays|content/i', $query)) {
            $features[] = 'writing';
        }
        if (preg_match('/image|draw|picture|photo|dall-e|imagen|art/i', $query)) {
            $features[] = 'image-generation';
        }
        if (preg_match('/file|pdf|csv|document|upload|analyze/i', $query)) {
            $features[] = 'file-upload';
        }
        if (preg_match('/voice|speech|speak|conversation/i', $query)) {
            $features[] = 'voice';
        }
        if (preg_match('/api|developer access|endpoint/i', $query)) {
            $features[] = 'api';
        }
        if (preg_match('/reasoning|logic|math|o1|o3|thinking/i', $query)) {
            $features[] = 'reasoning';
        }

        return array_unique($features);
    }

    /**
     * Generate database-backed explanation sentence without hallucinating fake specs.
     */
    private function generateExplanation(Product $product, ?array $plan, array $featureBreakdown, Country $country): string
    {
        $planText = $plan
            ? "{$product->name} {$plan['plan_name']} is priced at {$plan['formatted_price']} in {$country->name} (verified {$plan['verified_at']})."
            : "{$product->name} is available in {$country->name}.";

        $availableNames = [];
        foreach ($featureBreakdown as $info) {
            if ($info['available']) {
                $availableNames[] = strtolower($info['name']);
            }
        }

        if (! empty($availableNames)) {
            $featuresText = 'It includes verified support for '.implode(', ', $availableNames).'.';
        } else {
            $featuresText = 'Features are detailed in the comparison matrix.';
        }

        return "{$planText} {$featuresText}";
    }
}
