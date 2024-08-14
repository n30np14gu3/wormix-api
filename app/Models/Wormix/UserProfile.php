<?php

namespace App\Models\Wormix;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int user_id
 * @property int money
 * @property int real_money
 * @property int rating
 * @property int reaction_rate
 *
 * @property HasMany weapons
 * @property User user
 */
class UserProfile extends Model
{
    protected $table = 'wormix_user_profiles';

    protected $primaryKey = 'user_id';

    public function weapons() : HasMany
    {
        return $this->hasMany(UserWeapon::class, 'owner_id', 'user_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
