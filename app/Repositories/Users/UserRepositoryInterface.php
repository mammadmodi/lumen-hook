<?php

namespace App\Repositories\Users;

use App\User;

interface UserRepositoryInterface
{
    /**
     * Finds user by phone number.
     *
     * @param $phoneNumber
     * @return User
     */
    public function findByPhoneNumber($phoneNumber);
}
