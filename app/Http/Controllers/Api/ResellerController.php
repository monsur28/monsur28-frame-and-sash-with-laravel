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
       // Store Basic Information
       public function storeBasicInfo(Request $request)
       {
           $validator = Validator::make($request->all(), [
               'user_id'        => 'required|string|unique:resellers,user_id',
               'user_name'      => 'required|string',
               'first_name'     => 'nullable|string',
               'last_name'      => 'nullable|string',
               'user_email'     => 'required|email|unique:resellers,user_email',
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

           $reseller = Reseller::create([
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
               'user_id'        => 'required|integer|exists:resellers,user_id',
               'company_name'   => 'required|string',
               'company_image'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
               'nid'            => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
               'company_email'  => 'required|email|unique:resellers,company_email',
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 422);
           }

           $reseller = Reseller::where('user_id', $request->user_id)->first();

           if ($request->hasFile('company_image')) {
               $reseller->company_image = $request->file('company_image')->store('company_images');
           }

           if ($request->hasFile('nid')) {
               $reseller->nid = $request->file('nid')->store('nid_files');
           }

           $reseller->company_name = $request->company_name;
           $reseller->company_email = $request->company_email;
           $reseller->save();

           return response()->json(['message' => 'Company information saved successfully'], 200);
       }

       // Confirm and Approve reseller
       public function confirm(Request $request)
       {
           $validator = Validator::make($request->all(), [
               'user_id'   => 'required|integer|exists:resellers,user_id',
               'approved'  => 'required|boolean',
               'password'  => 'required|string|min:8',
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 422);
           }

           $reseller = Reseller::where('user_id', $request->user_id)->first();

           $reseller->approved = $request->approved;
           $reseller->password = Hash::make($request->password);
           $reseller->save();

           return response()->json(['message' => 'reseller confirmed and setup completed'], 200);
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

       // Delete reseller
       public function deletereseller(Request $request)
       {
           // Validate the user_id
           $validator = Validator::make($request->all(), [
               'user_id' => 'required|string|exists:resellers,user_id',
           ]);

           // If validation fails, return errors
           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 422);
           }

           // Find the reseller by user_id
           $reseller = Reseller::where('user_id', $request->user_id)->first();

           // If reseller is not found, return error
           if (!$reseller) {
               return response()->json(['message' => 'reseller not found'], 404);
           }

           // Delete the reseller
           $reseller->delete();

           return response()->json(['message' => 'reseller deleted successfully'], 200);
       }
}
