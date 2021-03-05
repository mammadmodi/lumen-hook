<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * Class User
 * @package App
 * @property int id
 * @property string name
 * @property string email
 * @property string phone_number
 * @property string password
 * @property integer activation_code
 * @property string status
 * @property string created_at
 */
class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    const STATUS_REGISTERED = "registered";
    const STATUS_VERIFIED = "verified";
    const STATUS_BANNED = "banned";

    /**
     * Returns all available states.
     *
     * @return array
     */
    public static function getStates()
    {
        return [self::STATUS_VERIFIED, self::STATUS_REGISTERED, self::STATUS_BANNED];
    }

    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone_number',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
