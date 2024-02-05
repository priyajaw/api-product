<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function index()
    {
        // $pdata = Product::all();
        $pdata = Product::with('variants')->get();

        $data = [
            'status' => 200,
            'product' => $pdata
        ];
        return response()->json($data, 200);
    }

    public function upload(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            
        ]);

        // Check for validation failure
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

      
        $product = Product::create($request->only(['name', 'description', 'price']));
        $data = [
            'status' => 200,
            'message' => 'Data upload successfully'
        ];
        return response()->json($data, 201);
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_id' => 'required',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
        ]);

        try{

            $product = Product::findOrFail($request->input('product_id'));
            $product->update($request->except('product_id'));
    
    
            return response()->json(['message' => 'Product updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product  not found'], 404);
        }

    }
    public function delete(Request $request, Product $product)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

    try{
            $product = Product::findOrFail($request->input('product_id'));

            // Delete variants associated with the product
            $product->variants()->delete();

            // Delete the product itself
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
    }

         catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product  not found'], 404);

       }

    }

 
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $searchQuery = $request->input('query');

        $products = Product::where('name', 'like', "%$searchQuery%")
        ->orWhere('description', 'like', "%$searchQuery%")
        ->orWhereHas('variants', function ($query) use ($searchQuery) {
            $query->where('name', 'like', "%$searchQuery%");
        })
        ->get();

        return response()->json(['products' => $products], 200);
    }
    }

