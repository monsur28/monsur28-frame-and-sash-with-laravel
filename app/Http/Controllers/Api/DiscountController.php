<?php

namespace App\Http\Controllers\Api;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    // Fetch all discounts
    public function index()
    {
        $discounts = Discount::all();
        return response()->json($discounts, 200);
    }

    // Store a new discount
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts',
            'description' => 'nullable|string',
            'expiry_date' => 'required|date',
        ]);

        $discount = Discount::create($validated);

        return response()->json([
            'message' => 'Discount created successfully.',
            'data' => $discount,
        ], 201);
    }

    // Update a discount
    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code,' . $id,
            'description' => 'nullable|string',
            'expiry_date' => 'required|date',
        ]);

        $discount->update($validated);

        return response()->json([
            'message' => 'Discount updated successfully.',
            'data' => $discount,
        ], 200);
    }

    // Delete a discount
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return response()->json([
            'message' => 'Discount deleted successfully.',
        ], 200);
    }
}
