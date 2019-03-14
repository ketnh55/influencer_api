<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTFactory;
use JWTAuth;
use App\User;
use Validator;
use Response;

class LoginAPIController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function user_login_api()
    {
        $customClaims = ['sub' => 'bar', 'aud' => 'bob'];

        $payload = JWTFactory::make($customClaims);

        $token = JWTAuth::encode($payload)->get();

        return response()->json(compact('token'));
    }

    public function user_register_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'id' => rand(0, 1000)
        ]);
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        return Response::json(compact('token'));
    }
}
