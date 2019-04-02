<?php

namespace App\Http\Controllers\api;

use App\Http\Services\UserSocialServices;
use App\Http\Controllers\Controller;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;
use App\User;
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
        $user->user_type !== null ? $user->require_update_info = 'false' :$user->require_update_info = 'true';
        return response()->json(['token'=>$token, 'user'=>$user]);
    }

    public function get_user_info(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        return response()->json(compact('user'));
    }

    public function user_login_api(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        $user = User::with('user_socials')->findOrFail($user->id);
        $user->user_type !== null ? $user->require_update_info = 'false' :$user->require_update_info = 'true';
        return response()->json(['allow_access_user' => true, 'user' => $user]);
    }
    public function user_update_info_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'sometimes|required|date_format:Y-m-d',
            'gender' => 'sometimes|required|string',
            'country' => 'sometimes|required|string',
            'location' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'user_type' => 'sometimes|required|numeric|min:1|max:2',
            'username' => 'sometimes|required|string',
            'email' => 'sometimes|required|string|email|max:255',
            'avatar' => 'sometimes|required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = JWTAuth::toUser($request->token);

        if ($user->user_type !== null && $request->get('user_type') !== null)
        {
            return response()->json(['error' => 'User type was existed']);
        }
        $user->date_of_birth = $request->get('date_of_birth')==null?$user->date_of_birth:$request->get('date_of_birth');
        $user->gender = $request->get('gender')==null?$user->gender:$request->get('gender');
        $user->country = $request->get('country')==null?$user->country:$request->get('country');
        $user->location = $request->get('location')==null?$user->location:$request->get('location');
        $user->description = $request->get('description')==null?$user->description:$request->get('description');
        $user->user_type = $request->get('user_type')==null?$user->user_type:$request->get('user_type');
        $user->username = $request->get('username')==null?$user->user_type:$request->get('username');
        $user->email = $request->get('email')==null?$user->user_type:$request->get('email');
        $user->avatar = $request->get('avatar')==null?$user->user_type:$request->get('avatar');
        $user->save();
        return response()->json(['update_user_info' => 'Success']);
    }
}
