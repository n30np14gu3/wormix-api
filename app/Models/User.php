<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int id
 * @property int social_id
 * @property string login
 * @property string password
 *
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'social_id',
        'login',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'password' => 'hashed'
    ];
}
