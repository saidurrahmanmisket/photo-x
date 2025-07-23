<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Effect extends Model
{
    protected $fillable = [
        'name',
        'image',
        'theme_id',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
