<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Hook
 *
 * @package App
 * @property int $id
 * @property int $user_id
 * @property string $url
 * @property string $cron
 * @property integer $threshold
 * @property string created_at
 * @property string $updated_at
 * @property User $user
 */
class Hook extends Model
{
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
}
