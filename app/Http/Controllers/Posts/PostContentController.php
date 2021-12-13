<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Post;
use App\Modules\Posts\Models\PostContent;
use App\Modules\Posts\Repositories\PostContentRepository;
use App\Modules\Posts\UseCases\PostUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostContentController extends Controller
{
    protected $postContentRepository;

    protected $postUseCase;

    public function __construct(PostContentRepository $contentRepository, PostUseCase $postUseCase)
    {
        $this->postContentRepository = $contentRepository;
        $this->postUseCase = $postUseCase;
    }

    public function getPostContent($postId)
    {
        $post = Post::whereId($postId)->with(['postContents' => function ($query) {
            $query->orderBy('position', 'ASC');
        }])->first();

        return compact('post');
    }

    public function storePostContents(Request $request, $postId)
    {
        $postContents = $this->postContentRepository->createContents($request->all(), $postId);

        return compact('postContents');
    }

    public function storePostContent(Request $request, $postId)
    {
        if ($request->text) {
            $content = [
                'post_id' => $postId,
                'text' => $request->text,
                'img' => null,
                'position' => $request->position,
            ];
        }else {
            $imgPath = $request->file('img')->store('images/blog');
            $content = [
                'post_id' => $postId,
                'text' => null,
                'img' => $imgPath,
                'position' => $request->position,
            ];
        }

        DB::table('post_contents')->insert($content);

        return compact('content');
    }

    public function updatePostContent(Request $request, $postContentId)
    {
        $content = PostContent::find($postContentId);
        if (strlen($request->text) > 0) {
            $content->text = $request->text;
        }else if($request->img) {
            $imgPath = $request->file('img')->store('images/blog');
            $content->img = $imgPath;
        }

        $content->update();

        return compact('content');
    }

    public function updatePositions(Request $request, $postId)
    {
        foreach ($request->all() as $item) {
            $content = PostContent::find($item['id']);

            $content->position = $item['position'];
            $content->update();
        }
    }

    public function deleteContent(Request $request, $postContentId)
    {
        $content = PostContent::find($postContentId);

        $content->delete();
    }

}
