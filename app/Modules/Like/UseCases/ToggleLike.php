<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/17/2020
 * Time: 3:37 PM
 */

namespace App\Modules\Like\UseCases;


use App\Modules\Like\Models\Like;
use App\Modules\Like\Services\LikeService;
use Illuminate\Support\Facades\Auth;

class ToggleLike
{
    public function perform(array $attributes)
    {
        $model = LikeService::getModel($attributes['model'], $attributes['model_id'], auth()->id());
        if ($model) {
            $model->liked = $model->liked ? 0 : 1;
            $model->update();
        }else {
            $attributes['liked'] = 1;
            $model = Like::create($attributes);
        }

        $count = LikeService::getCountLikesFromModel($attributes['model'], $attributes['model_id']);

        return ['liked' => $model->liked, 'countLikes' => $count];
    }
}