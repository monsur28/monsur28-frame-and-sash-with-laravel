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
    // Store Basic Information
    public function storeBasicInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|integer|unique:manufacturers,user_id',
            'user_name'      => 'required|string',
            'first_name'     => 'nullable|string',
            'last_name'      => 'nullable|string',
            'user_email'     => 'required|email|unique:manufacturers,user_email',
            'mobile_number'  => 'required|string|max:15',
            'country_region' => 'required|string',
            'language'       => 'required|string',
            'address'        => 'required|string',
            'city'           => 'required|string',
            'state'          => 'nullable|string',
            'zip_code'       => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $manufacturer = Manufacturer::create([
            'user_id'        => $request->user_id,
            'user_name'      => $request->user_name,
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'user_email'     => $request->user_email,
            'mobile_number'  => $request->mobile_number,
            'country_region' => $request->country_region,
            'language'       => $request->language,
            'address'        => $request->address,
            'city'           => $request->city,
            'state'          => $request->state,
            'zip_code'       => $request->zip_code,
        ]);

        return response()->json(['message' => 'Basic information saved successfully'], 201);
    }

    // Store Company Information
    public function storeCompanyInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'        => 'required|integer|exists:manufacturers,user_id',
            'company_name'   => 'required|string',
            'company_image'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'nid'            => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'company_email'  => 'required|email|unique:manufacturers,company_email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $manufacturer = Manufacturer::where('user_id', $request->user_id)->first();

        if ($request->hasFile('company_image')) {
            $manufacturer->company_image = $request->file('company_image')->store('company_images');
        }

        if ($request->hasFile('nid')) {
            $manufacturer->nid = $request->file('nid')->store('nid_files');
        }

        $manufacturer->company_name = $request->company_name;
        $manufacturer->company_email = $request->company_email;
        $manufacturer->save();

        return response()->json(['message' => 'Company information saved successfully'], 200);
    }

    // Confirm and Approve Manufacturer
    public function confirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|integer|exists:manufacturers,user_id',
            'approved'  => 'required|boolean',
            'password'  => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $manufacturer = Manufacturer::where('user_id', $request->user_id)->first();

        $manufacturer->approved = $request->approved;
        $manufacturer->password = Hash::make($request->password);
        $manufacturer->save();

        return response()->json(['message' => 'Manufacturer confirmed and setup completed'], 200);
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
