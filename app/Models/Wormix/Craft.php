<?php

namespace App\Models\Wormix;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int upgrade_id
 * @property int prev_upgrade_id
 *
 * @property string description
 *
 * @property int level
 * @property int required_level
 * @property array reagents
 */
class Craft extends Model
{
    protected $table = 'wormix_craft';

    protected $casts = [
        'reagents' => 'array'
    ];
}
