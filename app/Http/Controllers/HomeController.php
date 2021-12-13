<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/1/2020
 * Time: 4:31 AM
 */

namespace App\Http\Controllers;


use App\Modules\Comment\Models\Comment;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function getStatistics()
    {
        $comments = DB::table('comments')
            ->select([
                DB::raw('COUNT(id) as count'),
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date')
            ])
            ->groupBy('date')
            ->get();

        $likes = DB::table('likes')
            ->select([
                DB::raw('COUNT(id) as count'),
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date')
            ])
            ->groupBy('date')
            ->get();

        $users = DB::table('users')
            ->select([
                DB::raw('COUNT(id) as count'),
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date')
            ])
            ->groupBy('date')
            ->get();

        return compact('comments', 'likes', 'users');
    }
}