<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $fillable = [
        'name',
        'image',
        'grid_columns',
        'grid_rows',
        'price',
    ];

    public function effects()
    {
        return $this->belongsToMany(Effect::class, 'effect_frame');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function kiosks()
    {
        return $this->belongsToMany(Kiosk::class, 'frame_kiosk');
    }
}
