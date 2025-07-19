<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class KioskAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'status' => false, 
                'message' => 'Access token required', 
                'data' => [], 
                'code' => 401
            ], 401);
        }

        $booking = Booking::where('access_token', $token)
                         ->where('status', 'active')
                         ->where(function($query) {
                             $query->whereNull('expires_at')
                                   ->orWhere('expires_at', '>', Carbon::now());
                         })
                         ->first();

        if (!$booking) {
            return response()->json([
                'status' => false, 
                'message' => 'Invalid or expired access token', 
                'data' => [], 
                'code' => 401
            ], 401);
        }

        // Add booking to request for use in controllers
        $request->merge(['authenticated_booking' => $booking]);
        
        // Update last accessed time
        $booking->update(['last_accessed_at' => Carbon::now()]);

        return $next($request);
    }
} 