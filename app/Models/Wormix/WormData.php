<?php

namespace App\Models\Wormix;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 *
 * @property User owner
 */
class WormData extends Model
{
    protected $table = 'wormix_worms_data';
    protected $primaryKey = 'owner_id';

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
}
