<?php

namespace App\Repositories\Hooks;

use App\Hook;
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
    public function findByUser(User $user, $perPage = 10, $page = 1);

    /**
     * Finds Hooks of entry id.
     *
     * @param int $id
     * @return Hook
     */
    public function findById($id);
}
