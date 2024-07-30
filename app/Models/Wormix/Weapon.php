<?php

namespace App\Models\Wormix;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int id
 * @property string name
 * @property boolean is_starter
 * @property boolean hide_in_shop
 * @property int price
 * @property int real_price
 * @property boolean infinity
 * @property int required_friends
 * @property int required_level
 *
 * @property Weapon ref_weapon
 */
class Weapon extends Model
{
    protected $table = 'wormix_weapons';

    protected $fillable = [
        'id',
        'name',
        'is_starter',
        'hide_in_shop',
        'price',
        'real_price',
        'infinity',
        'required_level',
        'required_friends'
    ];

    public function ref_weapon() : HasOne
    {
        return $this->hasOne(Weapon::class, 'id', 'ref_id');
    }
}
