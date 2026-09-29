<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'active')->get();
        $features = Feature::all();

        $staticUrls = [
            route('home'),
            route('products.index'),
            route('compare.index'),
            route('finder.index'),
            route('calculator.index'),
            route('deals.index'),
            route('image_to_video.index'),
            route('image_to_image.index'),
            route('category.show', 'cheapest'),
        ];

        $productUrls = [];
        foreach ($products as $p) {
            $productUrls[] = route('products.show', $p->slug);
        }

        $categoryUrls = [];
        foreach ($features as $f) {
            $categoryUrls[] = route('category.show', $f->slug);
        }

        // Generate top comparison pair URLs (e.g. chatgpt-vs-claude)
        $compareUrls = [];
        for ($i = 0; $i < count($products); $i++) {
            for ($j = $i + 1; $j < count($products); $j++) {
                $compareUrls[] = route('compare.index', ['slugs' => "{$products[$i]->slug}-vs-{$products[$j]->slug}"]);
            }
        }

        return response()->view('public.sitemap.xml', compact(
            'staticUrls',
            'productUrls',
            'categoryUrls',
            'compareUrls'
        ))->header('Content-Type', 'text/xml');
    }
}
