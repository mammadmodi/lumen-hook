<?php

namespace App\Http\Controllers\V1;

use App\Hook;
use App\Http\Controllers\Controller;
use App\User;
use App\Repositories\Hooks\HookRepositoryInterface;
use Exception;
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
     * Returns rules for hook different type of requests.
     *
     * @param $method
     * @return array
     */
    public static function hookRules($method)
    {
        switch ($method) {
            case "store":
                return [
                    'url' => ['required', 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
                    'cron' => 'required|cron',
                    'threshold' => 'integer|max:10',
                ];
            case "update":
                return [
                    'url' => ['regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
                    'cron' => 'cron',
                    'threshold' => 'integer|max:10',
                ];
            default:
                return [];
        }
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
        $this->validate($request, self::hookRules('store'));

        $hook = new Hook($request->only(['url', 'cron', 'threshold']));
        $hook->user_id = auth()->user()->id;
        if (!$hook->save()) {
            return response(['message' => 'bad request'], 400);
        }

        // TODO schedule cron for hook
        return HookResource::make($hook);
    }

    /**
     * Updates an exist hook.
     *
     * @param int $id
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function update(int $id, Request $request)
    {
        $hook = $this->hookRepository->findById($id);
        if (empty($hook)) {
            return response()->json(["error" => "hook not found"], 404);
        }

        if (auth()->user()->id != $hook->user_id) {
            return response()->json(["error" => "you can only update your hooks"], 403);
        }

        $this->validate($request, self::hookRules('update'));

        if (!$hook->update($request->only(['url', 'cron', 'threshold']))) {
            return response(['message' => 'bad request'], 400);
        }

        // TODO reschedule cron for updated hook

        return response('', 204);
    }

    /**
     * Removes an exist hook.
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function delete(int $id)
    {
        $hook = $this->hookRepository->findById($id);
        if (empty($hook)) {
            return response()->json(["error" => "hook not found"], 404);
        }

        if (auth()->user()->id != $hook->user_id) {
            return response()->json(["error" => "you can only update your hooks"], 403);
        }

        try {
            $hook->delete();
        } catch (Exception $exception) {
            return response()->json(["error" => "could not remove hook"], 500);
        }

        // TODO remove hook from scheduler

        return response('', 204);
    }
}
