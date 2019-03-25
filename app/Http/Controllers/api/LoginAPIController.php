<?php

namespace App\Http\Controllers\api;

use App\Http\Services\UserSocialServices;
use App\Http\Controllers\Controller;
use JWTFactory;
use JWTAuth;
use App\User;
use Validator;
use Response;
use Illuminate\Http\Request;
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
    public function user_register_api(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        if (!$validated) {
            return response()->json($validated->errors());
        }

        try
        {
            $user = $this->socialAccountServices->createOrGetSocailUser($request);

        }
        catch (\Exception $e)
        {
            return response()->json(['error' => $e->getMessage()]);
        }
        $token = JWTAuth::fromUser($user);
        return response()->json([compact('token'), compact('user')]);
    }

    public function get_user_info(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        return response()->json(['data' => $user]);
    }

    public function user_login_api(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        $user->user_type !== null ? $user->require_update_info = 'false' :$user->require_update_info = 'true';
        return response()->json(['user' => $user, 'allow_access_user' => true]);
    }
    public function user_update_info_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'date_format:Y-m-d',
            'gender' => 'string',
            'country' => 'string',
            'location' => 'string',
            'description' => 'string',
            'user_type' => 'numeric|min:1|max:2'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = JWTAuth::toUser($request->token);

        if ($user->user_type !== null && $request->get('user_type') !== null)
        {
            return response()->json(['error' => 'User type was existed']);
        }
        $user->date_of_birth = $request->get('date_of_birth');
        $user->gender = $request->get('gender');
        $user->country = $request->get('country');
        $user->location = $request->get('location');
        $user->description = $request->get('description');
        $user->user_type = $request->get('user_type');
        $user->save();
        return response()->json(['update_user_info' => 'Success']);
    }
}
