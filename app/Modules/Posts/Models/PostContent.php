<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;

class PostContent extends Model
{
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
