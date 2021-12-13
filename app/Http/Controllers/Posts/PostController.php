<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Post;
use App\Modules\Posts\Requests\CreatePostRequest;
use App\Modules\Posts\Requests\TogglePublishRequest;
use App\Modules\Posts\Requests\UpdatePostRequest;
use App\Modules\Posts\UseCases\PostUseCase;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postUseCase;

    public function __construct(PostUseCase $postUseCase)
    {
        $this->postUseCase = $postUseCase;
    }

    public function getAll()
    {
        $posts = Post::all();

        return compact('posts');
    }

    public function store(CreatePostRequest $request)
    {
        $post = $this->postUseCase->create($request);

        return compact('post');
    }

    public function update(UpdatePostRequest $request, $postId)
    {
        $post = $this->postUseCase->update($request, $postId);

        return compact('post');
    }

    public function destroy($postId)
    {
        $this->postUseCase->destroy($postId);

        return response()->json('success', 200);
    }

    public function togglePublish(TogglePublishRequest $request)
    {
        $post = $this->postUseCase->togglePublish($request->postId);

        return compact('post');
    }
}
