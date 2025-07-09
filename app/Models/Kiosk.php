<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kiosk extends Model
{
    protected $fillable = [
        'name',
        'device_id',
        'status',
        'linked_user_id',
        'activation_code',
        'last_seen_at',
    ];

    public function linkedUser()
    {
        return $this->belongsTo(User::class, 'linked_user_id');
    }

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
}
