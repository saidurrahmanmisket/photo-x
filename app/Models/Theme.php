<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
    ];

    public function effects()
    {
        return $this->hasMany(Effect::class);
    }

    public function kiosks()
    {
        return $this->belongsToMany(Kiosk::class, 'kiosk_theme');
    }
}
