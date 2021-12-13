<?php


namespace App\Modules\Video\Repositories;


use App\Modules\Video\Models\Video;

class VideoRepository
{
    public function getAll()
    {
        $videos = Video::with(['category'])->get();

        return $videos;
    }
}