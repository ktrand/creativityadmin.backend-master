<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Modules\Comment\Jobs\SendNotification;
use App\Modules\Comment\Models\Comment;
use App\Modules\Comment\Requests\CommentStoreRequest;
use App\Modules\Video\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request, $videoId)
    {
        $video = Video::whereId($videoId)->first();
        $comment = (new Comment)->forceFill($request->all());
        $comment->user_id = auth()->id();
        $video->comments()->save($comment);

        $job = (new SendNotification($video, 'video'))->delay(Carbon::now()->addSeconds(20));

        dispatch($job);

        return compact('comment');
    }

    public function destroy(Request $request, $commentId)
    {
        $childComments = Comment::where('parent_id', $commentId)->pluck('id');

        if (count($childComments) > 0) {
            DB::table('comments')->whereIn('id', $childComments)->delete();
        }

        $comment = Comment::findOrFail($commentId);

        $comment->delete();
    }
}
