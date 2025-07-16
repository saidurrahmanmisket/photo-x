<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'status',
    ];

    public function media()
    {
        return $this->hasMany(AdvertisementMedia::class);
    }

    public function kiosks()
    {
        return $this->belongsToMany(Kiosk::class, 'advertisement_kiosk');
    }
} 