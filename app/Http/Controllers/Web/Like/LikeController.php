<?php

namespace App\Http\Controllers\Web\Like;

use App\Http\Controllers\Controller;
use App\Modules\Like\Requests\CountLikesRequest;
use App\Modules\Like\Requests\LikeRequest;
use App\Modules\Like\Services\LikeService;
use App\Modules\Like\UseCases\ToggleLike;

class LikeController extends Controller
{
    protected $liker;

    /**
     * LikeController constructor.
     * @param ToggleLike $liker
     */
    public function __construct(ToggleLike $liker)
    {
        $this->liker = $liker;
    }

    /**
     * @param LikeRequest $request
     * @return array
     */
    public function toggle(LikeRequest $request)
    {
        return $this->liker->perform($request->all());
    }

    /**
     * @param CountLikesRequest $request
     * @return mixed
     */
    public function getLikesCount(CountLikesRequest $request)
    {
        return LikeService::getCountLikesFromModel($request->model, $request->model_id);
    }
}
