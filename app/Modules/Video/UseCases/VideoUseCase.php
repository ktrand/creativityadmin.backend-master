<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 4/25/2020
 * Time: 4:25 PM
 */

namespace App\Modules\Video\UseCases;


use App\Modules\Video\Models\Video;

class VideoUseCase
{
    public function create($request)
    {
        $imgPath = $request->file('img')->store('images');

        $video = Video::create([
            'title' => $request->title,
            'img' => $imgPath,
            'category_id' => $request->category_id,
            'description' => $request->description
        ]);

        $video->load(['category']);

        return $video;
    }

    public function upload($request, $videoId)
    {
        $video = Video::find($videoId);

        if ($request->fromYoutube) {
            $videoPath = $request->youtubeLink;
            $video->uploaded_video = 0;
        } else {
            $videoPath = $request->file('video')->store('videos');
            $video->uploaded_video = 1;
        }

        $video->video = $videoPath;

        $video->update();

        $video->load(['category']);

        return $video;
    }

    public function update($request, $videoId)
    {
        $video = Video::find($videoId);

        $video->title = $request->title;
        $video->category_id = $request->category_id;
        if ($request->file('img')) {
            $imgPath = $request->file('img')->store('images');
            $video->img = $imgPath;
        }
        $video->description = $request->description;

        $video->update();

        $video->load(['category']);

        return $video;
    }

    public function destroy($videoId): void
    {
        $video = Video::findOrFail($videoId);

        $video->delete();
    }

    public function togglePublish($videoId)
    {
        $video = Video::findOrFail($videoId);
        $video->togglePublish();
        $video->update();

        $video->load(['category']);

        return $video;
    }
}