<?php

namespace App\Http\Controllers;

use App\Repositories\Users\UserRepositoryInterface;
use App\User;
use App\Http\Resources\User as UserResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Http\ResponseFactory;

class AuthController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * Create a new AuthController instance.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('auth:api', ['except' => ['login', 'register', "verify"]]);
    }

    /**
     * Register new user.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'phone_number' => 'required|regex:/^09\d{9}$/|max:12',
            'password' => 'required',
        ]);

        $user = new User($request->only(["name", "email", "phone_number"]));
        $user->password = Hash::make($request->input("password"));
        $user->activation_code = mt_rand(1000, 9999);

        if (!$user->save()) {
            return response()->json(['error' => 'user exist already'], 400);
        }

        // TODO send verification code through sms

        return response()->json([
            "message" => "user created successfully",
            "description" => "after you have been received activation code, verify your user"
        ], 200);
    }

    /**
     * Verifies user activation code.
     *
     * @param Request $request
     * @return JsonResponse|Response|ResponseFactory|object
     * @throws ValidationException
     */
    public function verify(Request $request)
    {
        $this->validate($request, [
            'phone_number' => 'required|regex:/^09\d{9}$/|max:12',
            'activation_code' => 'required',
        ]);

        $user = $this->userRepository->findByPhoneNumber($request->input("phone_number"));
        if (!$user instanceof User) {
            return response()->json(["error" => "user with entered phone number is not exist"], 404);
        }

        if ($user->activation_code != $request->input("activation_code")) {
            return response()->json(["error" => "activation code is invalid"], 401);
        }

        $user->status = User::STATUS_VERIFIED;
        $user->update();

        return response()->json(["message" => "now you can login with your email and password"], 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(UserResource::make(auth()->user()));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Carbon::now()->addSeconds(auth()->factory()->getTTL())->format("Y-m-d H:m:s")
        ]);
    }
}
