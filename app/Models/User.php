<?php

namespace App\Models;

use App\Models\Wormix\UserProfile;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int id
 * @property int social_id
 * @property string email
 * @property string login
 * @property string password
 *
 * @property UserProfile user_profile
 *
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'social_id',
        'email',
        'login',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'password' => 'hashed'
    ];

    public function user_profile() : HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }
}
