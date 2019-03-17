<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
class UserSocial extends Model
{
    //
    protected $table = 'social_users';
    protected $fillable = ['id', 'account_id', 'social_type','access_token', 'email', 'link'];

    public function user()
    {
        return $this->belongsTo(User::class, 'account_id');
    }
}
