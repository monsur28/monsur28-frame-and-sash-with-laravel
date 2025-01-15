<?php

namespace App\Http\Controllers\Api;

use App\Models\Reseller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ResellerController extends Controller
{
    public function storeReseller(Request $request)
    {
        // Validate all data at once
        $validator = Validator::make($request->all(), [
            // Basic information
            'user_id'        => 'required|string|unique:resellers,user_id,' . ($request->id ?? 'NULL'),
            'user_name'      => 'required|string',
            'first_name'     => 'nullable|string',
            'last_name'      => 'nullable|string',
            'user_email'     => 'required|email|unique:resellers,user_email,' . ($request->id ?? 'NULL'),
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
            'company_email'  => 'nullable|email|unique:resellers,company_email,' . ($request->id ?? 'NULL'),

            // Approval and password
            'approved'       => 'nullable|boolean',
            'password'       => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if reseller exists (for update, if ID is provided)
        $reseller = Reseller::where('user_id', $request->user_id)->first();

        if (!$reseller) {
            $reseller = new Reseller;
        }

        // Basic information update or creation
        $reseller->user_id        = $request->user_id;
        $reseller->user_name      = $request->user_name;
        $reseller->first_name     = $request->first_name;
        $reseller->last_name      = $request->last_name;
        $reseller->user_email     = $request->user_email;
        $reseller->mobile_number  = $request->mobile_number;
        $reseller->country_region = $request->country_region;
        $reseller->language       = $request->language;
        $reseller->address        = $request->address;
        $reseller->city           = $request->city;
        $reseller->state          = $request->state;
        $reseller->zip_code       = $request->zip_code;
        $reseller->company_name   = $request->company_name;

        // Handle file uploads if available
        if ($request->hasFile('company_image')) {
            $reseller->company_image = $request->file('company_image')->store('company_images');
        }

        if ($request->hasFile('nid')) {
            $reseller->nid = $request->file('nid')->store('nid_files');
        }

        // Update company email if provided
        if ($request->has('company_email')) {
            $reseller->company_email = $request->company_email;
        }

        if ($request->filled('password')) {
            $reseller->password = Hash::make($request->password);
        }

        if ($request->has('approved')) {
            if ($request->approved == 1) {
                $reseller->approved = 1;
                // Save or update rese$reseller record
                 $reseller->save();
            } else {
                return response()->json(['message' => 'Data cannot be saved unless approved is set to 1'], 403);
            }
        }

        return response()->json(['message' => 'Reseller information saved successfully'], 200);
    }

       // Get reseller Information
       public function getresellerDetails($user_id)
       {
           $reseller = Reseller::where('user_id', $user_id)->first();

           if (!$reseller) {
               return response()->json(['message' => 'reseller not found'], 404);
           }

           return response()->json([
               'user_id'         => $reseller->user_id,
               'user_name'       => $reseller->user_name,
               'first_name'      => $reseller->first_name,
               'last_name'       => $reseller->last_name,
               'user_email'      => $reseller->user_email,
               'mobile_number'   => $reseller->mobile_number,
               'country_region'  => $reseller->country_region,
               'language'        => $reseller->language,
               'address'         => $reseller->address,
               'city'            => $reseller->city,
               'state'           => $reseller->state,
               'zip_code'        => $reseller->zip_code,
               'company_name'    => $reseller->company_name,
               'company_email'   => $reseller->company_email,
               'company_image'   => $reseller->company_image ? Storage::url($reseller->company_image) : null,
               'nid'             => $reseller->nid ? Storage::url($reseller->nid) : null,
               'approved'        => $reseller->approved,
           ], 200);
       }

       //Get all resellers
         public function getAllResellers()
         {
              $resellers = Reseller::all();

              return response()->json($resellers, 200);
         }
       // Delete reseller
       public function deleteReseller($user_id)
       {
           // Find the manufacturer by user_id
           $manufacturer = Reseller::where('user_id', $user_id)->first();

           // If manufacturer is not found, return error
           if (!$manufacturer) {
               return response()->json(['message' => 'Manufacturer not found'], 404);
           }

           // Delete the manufacturer
           $manufacturer->delete();

           return response()->json(['message' => 'Manufacturer deleted successfully'], 200);
       }
}
