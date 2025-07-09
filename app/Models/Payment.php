<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'kiosk_id',
        'photo_id',
        'booking_id',
        'amount',
        'status',
        'payment_gateway',
        'transaction_id',
    ];

    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function kiosk()
    {
        return $this->belongsTo(Kiosk::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
