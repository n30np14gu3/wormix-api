<?php

namespace App\Models\Wormix;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int owner_id
 * @property int weapon_id
 * @property int count
 */
class UserWeapon extends Model
{
    protected $table = 'wormix_users_weapons';
}
