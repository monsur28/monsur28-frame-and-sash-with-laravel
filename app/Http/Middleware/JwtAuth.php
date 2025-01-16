<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth as JWT;

class JwtAuth
{
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            // Validate the token and get the authenticated user
            $user = JWT::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
            }

            // Check if the user's role matches any of the required roles
            if (!empty($roles) && !in_array($user->role, $roles)) {
                return response()->json(['status' => 'error', 'message' => 'Access denied'], 403);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => 'error', 'message' => 'Token expired'], 401);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 'error', 'message' => 'Token invalid'], 401);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return response()->json(['status' => 'error', 'message' => 'Token not provided'], 401);
            } else {
                return response()->json(['status' => 'error', 'message' => 'An error occurred'], 500);
            }
        }

        return $next($request);
    }
}
