<?php

namespace App\Repositories\Users;

use App\User;

class SqlUserRepository implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findByPhoneNumber($phoneNumber)
    {
        $user = User::query()
            ->where('phone_number', '=', $phoneNumber)
            ->first()
        ;

        return $user;
    }
}
