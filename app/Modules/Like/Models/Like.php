<?php

namespace App\Modules\Like\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $guarded = [];

    public function model()
    {
        return $this->morphTo();
    }

    public function scopeLiked($query)
    {
        return $query->where('liked', 1);
    }

    public function scopeVideo($query)
    {
        return $query->where('model', 'video');
    }
}
