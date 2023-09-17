<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    //

    public function unAuth(){
        return response()->json([
            "success" => false,
            "message" => "Unauthorized"

        ], 401);
    }
}
