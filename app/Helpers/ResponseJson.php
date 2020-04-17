<?php

namespace App\Helpers;

class ResponseJson
{
    public static function sendResponse($status,$data,$code)
    {
        return response()->json([
            'status'=>$status,
            'data'=>$data
        ],$code);
    }
}
