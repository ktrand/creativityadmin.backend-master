<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\Controller;
use App\Modules\Video\Repositories\VideoRepository;
use App\Modules\Video\Requests\TogglePublishRequest;
use App\Modules\Video\Requests\CreateVideoRequest;
use App\Modules\Video\Requests\UpdateVideoRequest;
use App\Modules\Video\Requests\UploadVideoRequest;
use App\Modules\Video\UseCases\VideoUseCase;

class VideoController extends Controller
{
    private $videoUseCase;

    private $videoRepository;

    public function __construct
    (
        VideoUseCase $videoUseCase,
        VideoRepository $videoRepository
    )
    {
        $this->videoUseCase = $videoUseCase;
        $this->videoRepository = $videoRepository;
    }

    public function getAll()
    {
        $videos = $this->videoRepository->getAll();

        return compact('videos');
    }

    public function store(CreateVideoRequest $request)
    {
        $video = $this->videoUseCase->create($request);

        return compact('video');
    }

    public function uploadVideo(UploadVideoRequest $request, $videoId)
    {
        $video = $this->videoUseCase->upload($request, $videoId);

        return compact('video');
    }

    public function update(UpdateVideoRequest $request, $videoId)
    {
        $video = $this->videoUseCase->update($request, $videoId);

        return compact('video');
    }

    public function destroy($videoId)
    {
        $this->videoUseCase->destroy($videoId);

        return response()->json('success', 200);
    }

    public function togglePublish(TogglePublishRequest $request)
    {
        $video = $this->videoUseCase->togglePublish($request->videoId);

        return compact('video');
    }
}
