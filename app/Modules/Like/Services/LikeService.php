<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/17/2020
 * Time: 3:34 PM
 */

namespace App\Modules\Like\Services;


use App\Modules\Like\Models\Like;
use App\Modules\Video\Models\Video;

class LikeService
{
    /**
     * @param $model
     * @param $modelId
     * @param $userId
     * @return mixed
     */
    public static function getModel($model, $modelId, $userId)
    {
        return Like::where('model', $model)
            ->where('model_id', $modelId)
            ->where('user_id', $userId)
            ->first();
    }

    public static function getCountLikesFromModel($model, $modelId)
    {
        return Like::where('model', $model)
            ->where('model_id', $modelId)
            ->liked()
            ->get()
            ->count();
    }
}