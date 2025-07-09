<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Effect extends Model
{
    protected $fillable = [
        'name',
        'image',
        'frame_id',
    ];

    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
