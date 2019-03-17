<?php

namespace App\Http\Controllers\api;

use App\Http\Services\UserSocialServices;
use App\Http\Controllers\Controller;
use JWTFactory;
use JWTAuth;
use App\User;
use Validator;
use Response;
use App\Http\Requests\UserRegisterRequest;

class LoginAPIController extends Controller
{

    protected $socialAccountServices;

    /**
     * LoginAPIController constructor.
     * @param UserSocialServices $socialAccountServices
     */
    public function __construct(UserSocialServices $socialAccountServices)
    {
        $this->socialAccountServices = $socialAccountServices;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user_login_api(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        if (!$validated) {
            return response()->json($validated->errors());
        }

        $user = $this->socialAccountServices->createOrGetSocailUser($request);
        $token = JWTAuth::fromUser($user);
        return response()->json([compact('token'), compact('user')]);
    }
}
