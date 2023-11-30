<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone_number',
        'can_create_loads',
        'username',
        'password',
        'company',
        'role'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }


    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }
}
