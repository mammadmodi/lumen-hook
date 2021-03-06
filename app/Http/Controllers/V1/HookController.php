<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\User;
use App\Repositories\Hooks\HookRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use App\Http\Resources\Hook as HookResource;

class HookController extends Controller
{
    /**
     * @var HookRepositoryInterface
     */
    private $hookRepository;

    /**
     * Create a new HookController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->hookRepository = app(HookRepositoryInterface::class);
    }

    /**
     * Display list of user's hooks.
     *
     * @param Request $request
     * @return AnonymousResourceCollection|Response
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $page = (int)$request->get('page') ?? 1;
        $hooks = $this->hookRepository->findByUser($user, 10, $page);

        return HookResource::collection($hooks);
    }
}
