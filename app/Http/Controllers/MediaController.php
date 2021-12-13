<?php


namespace App\Http\Controllers;


use App\Modules\Posts\Models\Post;
use App\Modules\Video\Models\Video;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function getImage($path)
    {
        return Storage::get("images/".$path);
    }

    public function getBlogImage($path)
    {
        return Storage::get("images/blog/".$path);
    }

    public function getVideo($videoId)
    {
        return Storage::get("videos/".$videoId);
    }

    public function search($query)
    {
        $videos = Video::where('title', 'LIKE', '%'.$query.'%')
            ->freeVideos()
            ->published()
            ->get()
            ->toArray();
        $posts = Post::where('title', 'LIKE', '%'.$query.'%')
            ->published()
            ->get()
            ->toArray();

        $data = array_merge($posts, $videos);

        return compact('data');
    }

    public function getVideosByCategory($slug)
    {
        $videos = Video::with(['category' => function($query) use($slug) {
            $query->where('slug', $slug);
        }])->freeVideos()->published()->get();

        return compact('videos');
    }
}