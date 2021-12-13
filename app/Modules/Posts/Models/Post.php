<?php

namespace App\Modules\Posts\Models;

use App\Modules\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    public function togglePublish()
    {
        $this->published = $this->published ? 0:1;
    }

    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    public function postContents()
    {
        return $this->hasMany(PostContent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
