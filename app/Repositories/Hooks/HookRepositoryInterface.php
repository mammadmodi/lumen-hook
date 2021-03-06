<?php

namespace App\Repositories\Hooks;

use App\User;
use Illuminate\Database\Eloquent\Collection;

interface HookRepositoryInterface
{
    /**
     * Finds Hooks of entry user.
     *
     * @param User $user
     * @param int $perPage
     * @param int $page
     * @return Collection
     */
    public function findByPhoneNumber(User $user, $perPage = 10, $page = 1);
}
