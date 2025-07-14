<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    public function media()
    {
        return $this->hasMany(AdvertisementMedia::class);
    }
} 