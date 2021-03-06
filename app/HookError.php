<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HookError
 *
 * @package App
 * @property int $status_code
 * @property string $response_body
 * @property int $hook_id
 * @property string $deleted_at
 * @property string $created_at
 * @property Hook $hook
 */
class HookError extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status_code',
        'response_body',
    ];

    protected $hidden = [
        'hook_id',
    ];

    /**
     * Get the related hook object.
     *
     * @return BelongsTo
     */
    public function hook()
    {
        return $this->belongsTo(Hook::class, 'hook_id');
    }
}
