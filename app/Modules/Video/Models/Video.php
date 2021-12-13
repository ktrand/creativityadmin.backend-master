<?php

namespace App\Modules\Video\Models;

use App\Modules\Category\Models\Category;
use App\Modules\Comment\Models\Comment;
use App\Modules\Like\Models\Like;
use App\Modules\Video\Services\VideoStatusServic;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Video extends Model
{
    use UsesUuid, Notifiable;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('commentCount', function ($builder) {
            $builder->withCount('comments');
        });
    }

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function togglePublish()
    {
        $this->published = $this->published ? 0:1;
    }

    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    public function scopeFreeVideos($query)
    {
        return $query->where('video_payment_status_id', VideoStatusServic::FREE_PAYMENT_STATUS_ID);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getThreadedComments()
    {
        return $this->comments()->with('owner')->get()->threaded();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'model', 'model');
    }
}
