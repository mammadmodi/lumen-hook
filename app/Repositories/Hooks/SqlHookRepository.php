<?php

namespace App\Repositories\Hooks;

use App\Hook;
use App\HookError;
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

    /**
     * @inheritDoc
     */
    public function findById($id)
    {
        /** @var Hook $hook */
        $hook = Hook::where('id', '=', $id)
            ->first();

        return $hook;
    }

    /**
     * @inheritDoc
     */
    public function getHookErrors(Hook $hook, $perPage = 10, $page = 1)
    {
        $hookErrors = HookError::where('hook_id', '=', $hook->id)
            ->orderBy('id', 'DESC')
            ->paginate($perPage, ['*'], 'page', $page)
            ->all();

        return $hookErrors;
    }

    /**
     * @inheritDoc
     */
    public function findHookErrorById($hookErrorId)
    {
        /** @var HookError $hookError */
        $hookError = HookError::with("hook")
            ->where('id', '=', $hookErrorId)
            ->first();

        return $hookError;
    }
}
