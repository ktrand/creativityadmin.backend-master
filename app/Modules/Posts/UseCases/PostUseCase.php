<?php

namespace App\Modules\Posts\UseCases;


use App\Modules\Posts\Models\Post;
use App\Modules\Video\Models\Video;

class PostUseCase
{
    public function create($request)
    {
        $imgPath = $request->file('img')->store('images/blog');

        $post = Post::create([
            'title' => $request->title,
            'img' => $imgPath,
        ]);

        return $post;
    }

    public function upload($request, $postId)
    {
        $video = Video::find($postId);

        if ($request->fromYoutube) {
            $videoPath = $request->youtubeLink;
            $video->uploaded_video = 0;
        } else {
            $videoPath = $request->file('video')->store('videos');
            $video->uploaded_video = 1;
        }

        $video->video = $videoPath;

        $video->update();

        return $video;
    }

    public function update($request, $postId)
    {
        $post = Post::find($postId);

        $post->title = $request->title;
        if ($request->file('img')) {
            $imgPath = $request->file('img')->store('images/blog');
            $post->img = $imgPath;
        }

        $post->update();

        return $post;
    }

    public function destroy($postId): void
    {
        $post = Post::findOrFail($postId);

        $post->delete();
    }

    public function togglePublish($postId)
    {
        $post = Post::findOrFail($postId);
        $post->togglePublish();
        $post->update();

        return $post;
    }
}