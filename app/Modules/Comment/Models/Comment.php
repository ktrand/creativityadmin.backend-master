<?php

namespace App\Modules\Comment\Models;

use App\Modules\Auth\Models\User;
use App\Modules\Comment\Services\CommentCollection;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function newCollection(array $models = [])
    {
        return new CommentCollection($models);
    }
}
