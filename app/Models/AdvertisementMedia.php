<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementMedia extends Model
{
    protected $fillable = [
        'advertisement_id',
        'file_path',
        'type',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
} 