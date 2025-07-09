<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'kiosk_id',
        'payment_type',
        'amount',
        'print_limit',
        'prints_used',
        'status',
        'expires_at',
        'transaction_id',
        'notes',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function kiosk()
    {
        return $this->belongsTo(Kiosk::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function canPrint()
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->payment_type === 'vendor') {
            return $this->prints_used < $this->print_limit;
        }

        return true; // User type has no print limit
    }

    public function incrementPrintsUsed()
    {
        $this->increment('prints_used');
    }
} 