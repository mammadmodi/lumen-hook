<?php

namespace App\Repositories\Hooks;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SqlHookRepository implements HookRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findByUser(User $user, $perPage = 10, $page = 1)
    {
        $hooks = DB::table('hooks')
            ->where('user_id', '=', $user->id)
            ->orderBy('id', 'DESC')
            ->paginate($perPage, ['*'], 'page', $page)
            ->items();
        $hooks = Collection::make($hooks);

        return $hooks;
    }
}
