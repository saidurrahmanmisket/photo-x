<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'avatar',
        'agree_to_terms',
        'is_premium',
        'email_verified_at',
    ];

    protected $hidden = [
        'email_verified_at',
        'agree_to_terms',
        'is_premium',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'agree_to_terms' => 'boolean',
        'is_premium' => 'boolean',
        'id' => 'integer',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'customer_id');
    }
}
