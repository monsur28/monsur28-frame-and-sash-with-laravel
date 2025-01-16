<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\SuperAdmin;
use App\Models\Reseller;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('user_email', 'password');

        $user = null;
        $userType = null;

        // Check Resellers table
        $user = Reseller::where('user_email', $credentials['user_email'])->first();
        $userType = 'reseller';

        // Check Manufacturers table if not found in Resellers
        if (!$user) {
            $user = Manufacturer::where('user_email', $credentials['user_email'])->first();
            $userType = 'manufacturer';
        }

        // Check Super Admins table if not found in Manufacturers
        // if (!$user) {
        //     $user = SuperAdmin::where('user_email', $credentials['user_email'])->first();
        //     $userType = 'super_admin';
        // }

        // If user not found in any table or password mismatch
        if (!$user || !$user->password || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

        // Generate JWT token with custom claims
        $token = JWTAuth::fromUser($user, [
            'user_type' => $userType,
            'roles' => $user->roles
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'roles' => $user->roles,
                'user_type' => $userType
            ]
        ]);
    }

    // Logout Method
    public function logout(Request $request)
    {
        try {
            // Get the JWT token from the request
            $token = JWTAuth::getToken();

            // Parse the token and get the user information
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            // Get the user_email from the token payload to match credentials
            $userEmail = JWTAuth::getPayload()->get('user_email');
            $userPassword = JWTAuth::getPayload()->get('password'); // Assuming password is also saved in the payload

            // Check the credentials in all three tables (Reseller, Manufacturer, SuperAdmin)
            $user = null;
            $userType = null;

            // Check Resellers table
            $user = Reseller::where('user_email', $userEmail)->first();
            $userType = 'reseller';

            // Check Manufacturers table if not found in Resellers
            if (!$user) {
                $user = Manufacturer::where('user_email', $userEmail)->first();
                $userType = 'manufacturer';
            }

            // Check Super Admins table if not found in Manufacturers
            // if (!$user) {
            //     $user = SuperAdmin::where('user_email', $userEmail)->first();
            //     $userType = 'super_admin';
            // }

            // If user not found or password mismatch
            if (!$user || !Hash::check($userPassword, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // If user is found, invalidate the token
            JWTAuth::invalidate($token);

            return response()->json([
                'status' => 'success',
                'message' => 'Logout successful'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout, please try again'
            ], 500);
        }
    }

}
