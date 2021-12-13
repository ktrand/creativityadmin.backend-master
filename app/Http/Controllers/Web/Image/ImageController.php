<?php

namespace App\Http\Controllers\Web\Image;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function getImage($path)
    {
        $contents = Storage::get('images/fQShx1k7rLZwjg7SD0D2dCE1TvmzHKTLd6kWHl49.png');

        $type = 'image/png';
        $responce = Response::make($contents, 200);
        $responce->header("Content-Type", $type);

        return $responce;
    }
}
