<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone_number',
        'can_create_loads',
        'username',
        'password',
        'company'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user');
    }
}
