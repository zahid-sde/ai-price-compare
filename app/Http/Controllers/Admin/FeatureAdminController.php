<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Product;
use App\Models\ProductFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeatureAdminController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('category')->orderBy('name')->get();

        return view('admin.features.index', compact('features'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Feature::create($validated);

        return redirect()->route('admin.features.index')->with('success', 'Feature created successfully.');
    }

    public function matrix()
    {
        $products = Product::orderBy('name')->get();
        $features = Feature::orderBy('category')->orderBy('name')->get();
        $matrix = ProductFeature::all()->keyBy(fn ($item) => "{$item->product_id}_{$item->feature_id}");

        return view('admin.features.matrix', compact('products', 'features', 'matrix'));
    }

    public function updateMatrix(Request $request)
    {
        $matrixData = $request->input('matrix', []);

        foreach ($matrixData as $productId => $featureData) {
            foreach ($featureData as $featureId => $data) {
                ProductFeature::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'feature_id' => $featureId,
                    ],
                    [
                        'is_available' => isset($data['is_available']) && $data['is_available'] == 1,
                        'notes' => $data['notes'] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('admin.features.matrix')->with('success', 'Product feature comparison matrix updated.');
    }
}
