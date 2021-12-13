<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/16/2020
 * Time: 4:15 PM
 */

namespace App\Modules\Comment\Services;


use Illuminate\Database\Eloquent\Collection;

class CommentCollection extends Collection
{
    public function threaded()
    {
        $comments = parent::groupBy('parent_id');

        if (count($comments)) {
            $comments['root'] = $comments[''];
            unset($comments['']);
        }

        return $comments;
    }
}