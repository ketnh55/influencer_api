<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
class UserSocial extends Model
{
    //
    protected $table = 'social_users';
    protected $fillable = ['user_id', 'social_type','sns_access_token', 'email', 'link', 'platform_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
