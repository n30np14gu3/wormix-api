<?php

namespace App\Models\Wormix;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int owner_id
 *
 * @property int armor
 * @property int attack
 *
 * @property int level
 * @property int experience
 *
 * @property int hat
 */
class WormData extends Model
{
    protected $table = 'wormix_worms_data';
    protected $primaryKey = 'owner_id';
}
