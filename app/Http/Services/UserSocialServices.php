<?php
/**
 * Created by PhpStorm.
 * User: kate
 * Date: 3/15/2019
 * Time: 11:30 PM
 */

namespace App\Http\Services;
use App\Model\UserSocial;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserSocialServices
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function createOrGetSocailUser(Request $request)
    {
        /*
         * Check if user exists or not
         * */
        $userSocial = UserSocial::where(['platform_id' => $request->get('sns_account_id'), 'social_type'=>$request->get('social_type')])->first();
        if($userSocial)
        {
            $user = $userSocial->user;
            $user = User::with('user_socials')->findOrFail($user->id);

            return $user;
        }

        //if not
        $user = User::create([
            'email'=>$request->get('email'),
            'username'=>$request->get('username'),
        ]);
        // create social user with main user
        $acc = new UserSocial([
            'platform_id' => $request->get('sns_account_id'),
            'social_type' => $request->get('social_type'),
            'sns_access_token' => $request->get('sns_access_token'),
            'email' => $request->get('email'),
            'link' => $request->get('link'),
        ]);
        $acc->user()->associate($user);
        $acc->save();
        $user = User::with('user_socials')->findOrFail($user->id);
        return $user;
    }

    public function updateUserInfo(User $user)
    {

    }
}