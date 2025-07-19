<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kiosk;
use App\Models\Frame;
use App\Models\Effect;
use App\Models\Advertisement;
use App\Models\Booking;
use App\Traits\ApiResponse;
use Carbon\Carbon;

class KioskDataController extends Controller
{
    use ApiResponse;

    /**
     * Get frames assigned to the authenticated kiosk
     */
    public function getFrames(Request $request)
    {
        $booking = $this->getAuthenticatedBooking($request);
        if (!$booking) {
            return $this->error([], 'Unauthorized', 401);
        }

        $kiosk = Kiosk::find($booking->kiosk_id);
        $frames = $kiosk->frames()->with('effects')->get();

        return $this->success($frames, 'Frames retrieved successfully');
    }

    /**
     * Get effects assigned to the authenticated kiosk
     */
    public function getEffects(Request $request)
    {
        $booking = $this->getAuthenticatedBooking($request);
        if (!$booking) {
            return $this->error([], 'Unauthorized', 401);
        }

        $kiosk = Kiosk::find($booking->kiosk_id);
        
        // Get effects that are assigned to frames that are assigned to this kiosk
        $effects = Effect::whereHas('frames', function($query) use ($kiosk) {
            $query->whereHas('kiosks', function($q) use ($kiosk) {
                $q->where('kiosks.id', $kiosk->id);
            });
        })->get();

        return $this->success($effects, 'Effects retrieved successfully');
    }

    /**
     * Get advertisements assigned to the authenticated kiosk
     */
    public function getAdvertisements(Request $request)
    {
        $booking = $this->getAuthenticatedBooking($request);
        if (!$booking) {
            return $this->error([], 'Unauthorized', 401);
        }

        $kiosk = Kiosk::find($booking->kiosk_id);
        $advertisements = $kiosk->advertisements()
                               ->with('media')
                               ->where('status', 'active')
                               ->get();

        return $this->success($advertisements, 'Advertisements retrieved successfully');
    }

    /**
     * Get all data for the authenticated kiosk (frames, effects, advertisements)
     */
    public function getAllData(Request $request)
    {
        $booking = $this->getAuthenticatedBooking($request);
        if (!$booking) {
            return $this->error([], 'Unauthorized', 401);
        }

        $kiosk = Kiosk::find($booking->kiosk_id);
        
        // Get frames with their effects
        $frames = $kiosk->frames()->with('effects')->get();
        
        // Get effects assigned to frames of this kiosk
        $effects = Effect::whereHas('frames', function($query) use ($kiosk) {
            $query->whereHas('kiosks', function($q) use ($kiosk) {
                $q->where('kiosks.id', $kiosk->id);
            });
        })->get();
        
        // Get active advertisements
        $advertisements = $kiosk->advertisements()
                               ->with('media')
                               ->where('status', 'active')
                               ->get();

        return $this->success([
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
            ],
            'frames' => $frames,
            'effects' => $effects,
            'advertisements' => $advertisements,
        ], 'Kiosk data retrieved successfully');
    }

    /**
     * Helper method to get authenticated booking
     */
    private function getAuthenticatedBooking(Request $request)
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return null;
        }

        $booking = Booking::where('access_token', $token)
                         ->where('status', 'active')
                         ->where(function($query) {
                             $query->whereNull('expires_at')
                                   ->orWhere('expires_at', '>', Carbon::now());
                         })
                         ->first();

        if ($booking) {
            // Update last accessed time
            $booking->update(['last_accessed_at' => Carbon::now()]);
        }

        return $booking;
    }
} 