<?php

namespace App\Models;

use App\Models\Wormix\UserProfile;
use App\Models\Wormix\WormData;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int id
 * @property int social_id
 * @property string email
 * @property string login
 * @property string password
 *
 * @property UserProfile user_profile
 * @property WormData worm_data
 * @property UserSocialData social_data
 *
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

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
        'password' => 'hashed',
        'social_id' => 'string'
    ];

    public function user_profile() : HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function worm_data() : HasOne
    {
        return $this->hasOne(WormData::class, 'owner_id', 'id');
    }

    public function social_data(): HasOne
    {
        return $this->hasOne(UserSocialData::class, 'user_id', 'id');
    }
}
