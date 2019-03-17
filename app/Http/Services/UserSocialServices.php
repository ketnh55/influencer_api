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
        $user = User::whereEmail($request->email)->whereNotNull('email')->first();
        if(!$user)
        {
            //var_dump(Hash::make($request->get('email') + $request->get('name')));
            //if not
            $user = new User([
                'email'=>$request->get('email'),
                'username'=>$request->get('name'),
                'password'=>Hash::make($request->get('email').$request->get('name'))
            ]);
            // create social user with main user
            $acc = new UserSocial([
                'id' => $request->get('id'),
                'social_type' => $request->get('social_type'),
                'access_token' => $request->get('token'),
                'email' => $request->get('email'),
                'link' => $request->get('link'),
            ]);
            $acc->user()->associate($user);
            //$acc->save();
        }

        return $user;
    }
}