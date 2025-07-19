<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kiosk;
use App\Models\Booking;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KioskAuthController extends Controller
{
    use ApiResponse;

    /**
     * Kiosk login with activation code
     */
    public function login(Request $request)
    {
        $request->validate([
            'activation_code' => 'required|string|max:255',
        ]);

        // Find kiosk by activation code
        $kiosk = Kiosk::where('activation_code', $request->activation_code)
                     ->where('status', 'active')
                     ->first();

        if (!$kiosk) {
            return $this->error([], 'Invalid activation code or kiosk inactive', 401);
        }

        // Check if there's an active booking for this kiosk
        $booking = Booking::where('kiosk_id', $kiosk->id)
                         ->where('status', 'active')
                         ->where(function($query) {
                             $query->whereNull('expires_at')
                                   ->orWhere('expires_at', '>', Carbon::now());
                         })
                         ->first();

        if (!$booking) {
            return $this->error([], 'No active booking found for this kiosk', 403);
        }

        // Generate secure token
        $token = hash('sha256', $kiosk->id . '|' . $booking->id . '|' . Str::random(32) . '|' . time());

        // Store token in booking for validation
        $booking->update(['access_token' => $token, 'last_accessed_at' => Carbon::now()]);

        return $this->success([
            'token' => $token,
            'kiosk' => [
                'id' => $kiosk->id,
                'name' => $kiosk->name,
                'max_clicks' => $kiosk->max_clicks,
                'max_capture_seconds' => $kiosk->max_capture_seconds,
                'max_idle_seconds' => $kiosk->max_idle_seconds,
            ],
            'booking' => [
                'id' => $booking->id,
                'print_limit' => $booking->print_limit,
                'prints_used' => $booking->prints_used,
                'payment_type' => $booking->payment_type,
                'expires_at' => $booking->expires_at,
            ]
        ], 'Kiosk authenticated successfully');
    }

    /**
     * Validate kiosk token
     */
    public function validateToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return $this->error([], 'Token required', 401);
        }

        $booking = Booking::where('access_token', $token)
                         ->where('status', 'active')
                         ->where(function($query) {
                             $query->whereNull('expires_at')
                                   ->orWhere('expires_at', '>', Carbon::now());
                         })
                         ->first();

        if (!$booking) {
            return $this->error([], 'Invalid or expired token', 401);
        }

        // Update last accessed time
        $booking->update(['last_accessed_at' => Carbon::now()]);

        return $this->success([
            'valid' => true,
            'booking_id' => $booking->id,
            'kiosk_id' => $booking->kiosk_id
        ], 'Token is valid');
    }

    /**
     * Logout kiosk (invalidate token)
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            Booking::where('access_token', $token)->update(['access_token' => null]);
        }

        return $this->success([], 'Logged out successfully');
    }
}
