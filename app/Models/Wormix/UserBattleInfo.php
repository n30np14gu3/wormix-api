<?php

namespace App\Models\Wormix;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property int battles_count
 * @property int last_battle_time
 * @property int mission_id
 */
class UserBattleInfo extends Model
{
    protected $table = 'wormix_users_battle_info';

    protected $fillable = [
        'user_id',
        'battles_count',
        'last_battle_time',
        'mission_id'
    ];
}
