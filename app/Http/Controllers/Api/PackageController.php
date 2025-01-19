<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    // Get all packages with their features
    public function index()
    {
        return Package::with('features')->get();
    }

    // Get a single package with features
    public function show($id)
    {
        $package = Package::with('features')->findOrFail($id);
        return response()->json($package);
    }

    // Create a new package with features
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'features' => 'nullable|array',
            'features.*.name' => 'required|string',
            'features.*.available' => 'required|boolean',
        ]);

        $package = Package::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'duration' => $validated['duration'],
        ]);

        if (!empty($validated['features'])) {
            foreach ($validated['features'] as $feature) {
                $package->features()->create([
                    'name' => $feature['name'],
                    'available' => $feature['available'],
                    'package_id' => $package->id, // Explicitly setting the package_id
                ]);
            }
        }

        return response()->json(['message' => 'Package created successfully', 'package' => $package], 201);
    }

    // Update a package with features
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'features' => 'nullable|array',
            'features.*.name' => 'required|string',
            'features.*.available' => 'required|boolean',
        ]);

        $package = Package::findOrFail($id);
        $package->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'duration' => $validated['duration'],
        ]);

        if (isset($validated['features'])) {
            // Sync features
            $package->features()->delete();
            foreach ($validated['features'] as $feature) {
                $package->features()->create([
                    'name' => $feature['name'],
                    'available' => $feature['available'],
                    'package_id' => $package->id,
                ]);
            }
        }

        return response()->json(['message' => 'Package updated successfully', 'package' => $package]);
    }

    // Delete a package and its features
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->features()->delete();
        $package->delete();

        return response()->json(['message' => 'Package deleted successfully']);
    }
}
