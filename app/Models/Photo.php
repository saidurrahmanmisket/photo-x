<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'customer_id',
        'kiosk_id',
        'frame_id',
        'effect_id',
        'booking_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
