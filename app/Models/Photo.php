<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'user_id',
        'kiosk_id',
        'frame_id',
        'effect_id',
        'booking_id',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function kiosk()
    {
        return $this->belongsTo(Kiosk::class);
    }

    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }

    public function effect()
    {
        return $this->belongsTo(Effect::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
