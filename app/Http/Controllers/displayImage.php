<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use App\Movie;
use Auth;
use App\User;


public function displayImage($filename)

{



    $path = storage_public('images/' . $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);



    return $response;

}
