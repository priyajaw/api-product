<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VariantController extends Controller
{
    public function store(Request $request)
    {
        $validator = $request->validate([
            'product_id' => 'required',
            'variants' => 'required|array',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.sku' => 'required|string|max:50',
            'variants.*.additional_cost' => 'required|numeric',
            'variants.*.stock_count' => 'required|integer',
        ]);
        try {



            $product = Product::findOrFail($request->input('product_id'));

            $variantsData = $request->input('variants');

            $createdVariants = [];

            foreach ($variantsData as $variantData) {
                $createdVariant = $product->variants()->create($variantData);
                $createdVariants[] = $createdVariant;
            }

            return response()->json($createdVariants, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'variants.*.id' => 'required',
            'variants.*.name' => 'sometimes|string|max:255',
            'variants.*.additional_cost' => 'sometimes|numeric',
            'variants.*.sku' => 'sometimes|string|max:50',

            'variants.*.stock_count' => 'sometimes|integer',
        ]);
        try {
            $variant = Variant::findOrFail($request->input('variant_id'));
            $variant->update($request->except('variant_id'));
            return response()->json(['message' => 'Variant updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'variant  not found'], 404);
        }
    }

    public function delete(Request $request, Product $product)
    {
        $request->validate([
            'variant_id' => 'required',
        ]);
        try {
            $variant = Variant::findOrFail($request->input('variant_id'));
            $variant->delete();

            return response()->json(['message' => 'Variant deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'variant  not found'], 404);
        }
    }
}
