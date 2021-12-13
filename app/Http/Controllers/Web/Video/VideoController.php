<?php

namespace App\Http\Controllers\Web\Video;

use App\Http\Controllers\Controller;
use App\Modules\Like\Models\Like;
use App\Modules\Video\Models\Video;
use App\WebModules\Video\Repositories\VideoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    private $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function getAll()
    {
        $videos = $this->videoRepository->getAll();

        return compact('videos');
    }

    public function getLikedVideos()
    {
        $userId = Auth::user()->id;

        $videos = DB::table('videos')
            ->select(['videos.*'])
            ->join('likes', 'videos.id', '=', 'likes.model_id')
            ->where('published', '=', 1)
            ->where('likes.liked', '=', 1)
            ->where('user_id', $userId)
            ->get();

        return compact('videos');
    }
}
