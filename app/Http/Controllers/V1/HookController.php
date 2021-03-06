<?php

namespace App\Http\Controllers\V1;

use App\Hook;
use App\Http\Controllers\Controller;
use App\User;
use App\Repositories\Hooks\HookRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use App\Http\Resources\Hook as HookResource;
use Illuminate\Validation\ValidationException;

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
     * Rules for hook requests
     *
     * @return array
     */
    public static function hookRules()
    {
        return [
            'url' => ['required', 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
            'cron' => 'required|cron',
            'threshold' => 'integer|max:10',
        ];
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

    /**
     * Tries to store a hook in the database.
     *
     * @param Request $request
     * @return HookResource
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, self::hookRules());

        $hook = new Hook($request->only(['url', 'cron', 'threshold']));
        $hook->user_id = auth()->user()->id;
        if (!$hook->save()) {
            return response(['message' => 'bad request'], 400);
        }

        // TODO schedule cron for hook
        return HookResource::make($hook);
    }
}
