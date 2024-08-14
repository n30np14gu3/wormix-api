<?php

namespace App\Models\Wormix;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 *
 * @property int battles_count
 *
 * @property int current_battle_id
 * @property array awards
 * @property int battle_type
 *
 * @property int mission_id
 *
 * @property int last_boss_fight_time
 * @property int last_battle_time
 *
 */
class UserBattleInfo extends Model
{
    protected $table = 'wormix_users_battle_info';

    protected $fillable = [
        'user_id',
        'battles_count',

        'mission_id',

        'last_boss_fight_time',
        'last_battle_time',
    ];

    protected $casts = [
        'awards' => 'json'
    ];

    protected $hidden = [
        'awards',
        'current_battle_id',
        'battle_type'
    ];
}
