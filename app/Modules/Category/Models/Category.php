<?php

namespace App\Modules\Category\Models;

use App\Modules\Video\Models\Video;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function videos()
    {
        return $this->hasMany(Video::class, 'id');
    }
}
