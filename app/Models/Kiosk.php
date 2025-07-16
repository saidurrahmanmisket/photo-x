<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kiosk extends Model
{
    protected $fillable = [
        'name',
        'device_id',
        'status',
        'activation_code',
        'max_clicks',
        'max_capture_seconds',
        'max_idle_seconds',
        'last_seen_at',
    ];


    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function frames()
    {
        return $this->belongsToMany(Frame::class, 'frame_kiosk');
    }

    public function advertisements()
    {
        return $this->belongsToMany(Advertisement::class, 'advertisement_kiosk');
    }
}
