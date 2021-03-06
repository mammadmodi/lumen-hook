<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Hook
 *
 * @package App
 * @property int $id
 * @property int $user_id
 * @property string $url
 * @property string $cron
 * @property integer $threshold
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property Collection $errors
 */
class Hook extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'url',
        'cron',
        'threshold',
    ];

    protected $hidden = [
        'user_id',
    ];

    /**
     * Get the user that requested this url.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get errors for this hook.
     *
     * @return HasMany
     */
    public function errors()
    {
        return $this->hasMany(Hook::class);
    }
}
