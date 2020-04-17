<?php 

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\AuthenticateController\AuthenticatePost;

class AuthenticateController extends Controller
{
    public function authenticate(AuthenticatePost $request)
    {
        $credential = $request->only('email','password');
        if(Auth::attempt($credential)){
            $data['token'] = Auth::user()->createToken('authToken')->accessToken;
            return ResponseJson::sendResponse('success',$data,200);
        }
        $data["messages"] = "email or password not correct";
        return ResponseJson::sendResponse('failed',$data,404);
    }
}
