<?php

use App\Hook;
use App\User;
use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * Generates a valid token.
     *
     * @param $user
     * @return string
     */
    protected function getValidToken($user)
    {
        return $this->makeToken($user);
    }

    /**
     * Generates a not valid token.
     *
     * @return string
     */
    protected function getNotValidToken()
    {
        $user = factory(User::class)->make(['name' => 'not_exist_user']);

        return $this->makeToken($user);
    }

    /**
     * Generates a valid token for entry user.
     *
     * @param User $user
     * @return string
     */
    protected function makeToken(User $user)
    {
        return JWTAuth::fromUser($user);
    }

    /**
     * Creates a new user.
     *
     * @return User
     */
    protected function createUser()
    {
        $user = factory(User::class)->create();

        return $user;
    }

    /**
     * Adds hooks for an user.
     *
     * @param User $user
     * @param int $count
     */
    protected function addHooksToUser(User $user, $count = 2)
    {
        factory(Hook::class, $count)->create(['user_id' => $user->id]);
    }

    /**
     * Gets an user with number of hooks.
     *
     * @param int $count
     * @return User
     */
    protected function getUserWithHooks($count = 10)
    {
        $user = $this->createUser();
        $this->addHooksToUser($user, $count);

        return $user;
    }
}
