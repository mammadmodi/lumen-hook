<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;

class VerifyUser
{
    /**
     * Verifies user.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->status != User::STATUS_VERIFIED) {
            return response()->json(["error" => "user is not verified"], 401);
        }

        return $next($request);
    }
}
