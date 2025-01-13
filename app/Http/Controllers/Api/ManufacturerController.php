<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManufacturerController extends Controller
{
    public function storeManufacturer(Request $request)
    {
        // Validate all data at once
        $validator = Validator::make($request->all(), [
            // Basic information
            'user_id'        => 'required|string|unique:manufacturers,user_id,' . ($request->id ?? 'NULL'),
            'user_name'      => 'required|string',
            'first_name'     => 'nullable|string',
            'last_name'      => 'nullable|string',
            'user_email'     => 'required|email|unique:manufacturers,user_email,' . ($request->id ?? 'NULL'),
            'mobile_number'  => 'required|string|max:15',
            'country_region' => 'required|string',
            'language'       => 'required|string',
            'address'        => 'required|string',
            'city'           => 'required|string',
            'state'          => 'nullable|string',
            'zip_code'       => 'nullable|string|max:10',

            // Company information
            'company_name'   => 'required|string',
            'company_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nid'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'company_email'  => 'nullable|email|unique:manufacturers,company_email,' . ($request->id ?? 'NULL'),

            // Approval and password
            'approved'       => 'nullable|boolean',
            'password'       => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if manufacturer exists (for update, if ID is provided)
        $manufacturer = Manufacturer::where('user_id', $request->user_id)->first();

        if (!$manufacturer) {
            $manufacturer = new Manufacturer;
        }

        // Basic information update or creation
        $manufacturer->user_id        = $request->user_id;
        $manufacturer->user_name      = $request->user_name;
        $manufacturer->first_name     = $request->first_name;
        $manufacturer->last_name      = $request->last_name;
        $manufacturer->user_email     = $request->user_email;
        $manufacturer->mobile_number  = $request->mobile_number;
        $manufacturer->country_region = $request->country_region;
        $manufacturer->language       = $request->language;
        $manufacturer->address        = $request->address;
        $manufacturer->city           = $request->city;
        $manufacturer->state          = $request->state;
        $manufacturer->zip_code       = $request->zip_code;
        $manufacturer->company_name   = $request->company_name;

        // Handle file uploads if available
        if ($request->hasFile('company_image')) {
            $manufacturer->company_image = $request->file('company_image')->store('company_images');
        }

        if ($request->hasFile('nid')) {
            $manufacturer->nid = $request->file('nid')->store('nid_files');
        }

        // Update company email if provided
        if ($request->has('company_email')) {
            $manufacturer->company_email = $request->company_email;
        }

        // Update approval and password if provided
        // if ($request->has('approved')) {
        //     $manufacturer->approved = $request->approved;
        // }

        if ($request->filled('password')) {
            $manufacturer->password = Hash::make($request->password);
        }

        // Save or update manufacturer record
        $manufacturer->save();

        return response()->json(['message' => 'Manufacturer information saved successfully'], 200);
    }

    // Get Manufacturer Information
    public function getManufacturerDetails($user_id)
    {
        $manufacturer = Manufacturer::where('user_id', $user_id)->first();

        if (!$manufacturer) {
            return response()->json(['message' => 'Manufacturer not found'], 404);
        }

        return response()->json([
            'user_id'         => $manufacturer->user_id,
            'user_name'       => $manufacturer->user_name,
            'first_name'      => $manufacturer->first_name,
            'last_name'       => $manufacturer->last_name,
            'user_email'      => $manufacturer->user_email,
            'mobile_number'   => $manufacturer->mobile_number,
            'country_region'  => $manufacturer->country_region,
            'language'        => $manufacturer->language,
            'address'         => $manufacturer->address,
            'city'            => $manufacturer->city,
            'state'           => $manufacturer->state,
            'zip_code'        => $manufacturer->zip_code,
            'company_name'    => $manufacturer->company_name,
            'company_email'   => $manufacturer->company_email,
            'company_image'   => $manufacturer->company_image ? Storage::url($manufacturer->company_image) : null,
            'nid'             => $manufacturer->nid ? Storage::url($manufacturer->nid) : null,
            'approved'        => $manufacturer->approved,
        ], 200);
    }

    // Delete Manufacturer
    public function deleteManufacturer(Request $request)
    {
        // Validate the user_id
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|exists:manufacturers,user_id',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the manufacturer by user_id
        $manufacturer = Manufacturer::where('user_id', $request->user_id)->first();

        // If manufacturer is not found, return error
        if (!$manufacturer) {
            return response()->json(['message' => 'Manufacturer not found'], 404);
        }

        // Delete the manufacturer
        $manufacturer->delete();

        return response()->json(['message' => 'Manufacturer deleted successfully'], 200);
    }
}
