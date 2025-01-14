<?php

namespace App\Http\Controllers\Api;

use App\Models\SiteInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteInfoController extends Controller
{
    // Fetch all site info
    public function index()
    {
        $siteInfo = SiteInfo::first();
        return response()->json($siteInfo, 200);
    }

    // Store or update site info
    public function store(Request $request)
    {
        $validated = $request->validate([
            'short_description' => 'nullable|string',
            'copy_right'        => 'nullable|string',
            'address'           => 'nullable|string',
            'map_link'          => 'nullable|url',
            'email'             => 'nullable|email',
            'phone'             => 'nullable|string',
        ]);

        // Update existing or create new record
        $siteInfo = SiteInfo::updateOrCreate(
            ['id' => 1], // Assuming single site info record
            $validated
        );

        return response()->json([
            'message' => 'Site information saved successfully.',
            'data'    => $siteInfo,
        ], 200);
    }
}
