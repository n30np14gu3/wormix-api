<?php

namespace App\Models\Wormix;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int money
 * @property int real_money
 * @property int rating
 * @property int reaction_rate
 */
class UserProfile extends Model
{
    protected $table = 'wormix_user_profiles';
}
