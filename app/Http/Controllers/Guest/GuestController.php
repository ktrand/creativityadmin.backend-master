<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7/2/2020
 * Time: 2:16 PM
 */

namespace App\Http\Controllers\Guest;


use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Post;
use App\Modules\Video\Models\Video;

class GuestController extends Controller
{
    public function getVideos()
    {
        $videos = Video::freeVideos()->published()->get();

        return compact('videos');
    }

    public function getVideo($videoId)
    {
        $video = Video::whereId($videoId)->with('comments.user')->freeVideos()->published()->first();

        return compact('video');
    }

    public function getRecommendationVideos($videoId)
    {
        $video = Video::whereId($videoId)->first();
        $videos = Video::where('category_id', $video->category_id)
                    ->where('id', '!=', $videoId)
                    ->freeVideos()
                    ->published()
                    ->limit(6)
                    ->get();

        return compact('videos');
    }

    public function getPosts()
    {
        $posts = Post::published()->get();

        return compact('posts');
    }

    public function getPostContents($postId)
    {
        $post = Post::whereId($postId)->with(['postContents' => function ($query) {
            $query->orderBy('position', 'ASC');
        }, 'user'])->first();

        return compact('post');
    }
}