<?php
/**
 * Created by PhpStorm.
 * User: kate
 * Date: 4/6/2019
 * Time: 10:57 AM
 */

namespace App\Http\Controllers\api;

use JWTFactory;
use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class CommonController extends  Controller
{
    public function get_user_info(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        $user = User::with('user_socials')->findOrFail($user->id);
        return response()->json(compact('user'));
    }
}