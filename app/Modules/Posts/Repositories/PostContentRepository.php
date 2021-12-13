<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/1/2020
 * Time: 7:30 PM
 */

namespace App\Modules\Posts\Repositories;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostContentRepository
{
    public function createContents(array $attr, $postId)
    {
        $data = [];
        foreach ($attr as $key => $value) {
            if (strripos($key, 'text') !== false) {
                $data[] = [
                    'post_id' => $postId,
                    'text' => $value,
                    'img' => null,
                    'position' => preg_replace('/[^0-9]/', '', $key),
                ];
            }else {
                $imgPath = Storage::disk('public')->putFile('images/blog', $value);
                $data[] = [
                    'post_id' => $postId,
                    'text' => null,
                    'img' => $imgPath,
                    'position' => preg_replace('/[^0-9]/', '', $key),
                ];
            }
        }

        DB::table('post_contents')->insert($data);

        return $data;
    }
}