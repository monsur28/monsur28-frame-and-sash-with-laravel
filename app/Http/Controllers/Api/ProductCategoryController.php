<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    // List all categories
    public function index()
    {
        return response()->json(ProductCategory::all(), 200);
    }

    // Show single category
    public function show($id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category, 200);
    }

    // Create a new category
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'accessories_available' => 'required|boolean',
            'accessories_attributes' => 'nullable|array',
            'ingredients_attributes' => 'nullable|array',
            'working_hour_available' => 'required|boolean',
            'wholesale_price_available' => 'required|boolean',
            'market_price_available' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->except('category_image');

        // Handle image upload
        if ($request->hasFile('category_image')) {
            $data['category_image'] = $request->file('category_image')->store('category_images', 'public');
        }

        $category = ProductCategory::create($data);

        return response()->json($category, 201);
    }

    // Update an existing category
    public function update(Request $request, $id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_name' => 'sometimes|required|string|max:255',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'accessories_available' => 'sometimes|required|boolean',
            'accessories_attributes' => 'nullable|array',
            'ingredients_attributes' => 'nullable|array',
            'working_hour_available' => 'sometimes|required|boolean',
            'wholesale_price_available' => 'sometimes|required|boolean',
            'market_price_available' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->except('category_image');

        // Handle image upload
        if ($request->hasFile('category_image')) {
            $data['category_image'] = $request->file('category_image')->store('category_images', 'public');
        }

        $category->update($data);

        return response()->json($category, 200);
    }

    // Delete a category
    public function destroy($id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
