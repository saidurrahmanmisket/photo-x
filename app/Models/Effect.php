<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Effect extends Model
{
    protected $fillable = [
        'name',
        'image',
    ];

    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function frames()
    {
        return $this->belongsToMany(Frame::class, 'effect_frame');
    }
}
