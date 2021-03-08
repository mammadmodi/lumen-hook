<?php

namespace Feature\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseTransactions;
use TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function login_with_bad_credentials()
    {
        $this->post('auth/login', ['email' => 'man@gmail.com', 'password' => 'wrong_password'])->response;
        $this->assertResponseStatus(401);
    }

    /**
     * @test
     */
    public function login_with_right_credentials()
    {
        $email = 'right@email.com';
        $password = 'right_password';
        $user = factory(User::class)->create([
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $this->post('auth/login', ['email' => $user->email, 'password' => $password]);
        $this->assertResponseOk();
        $this->assertTrue($this->responseHasFields(["access_token", "expires_in", "token_type"]));
    }

    /**
     * @test
     */
    public function successful_logout()
    {
        $user = $this->createUser();
        $validToken = $this->getValidToken($user);
        $this->get('auth/logout', ['Authorization' => 'Bearer ' . $validToken, 'Accept' => 'application/json']);
        $this->assertResponseOk();
        $this->assertTrue($this->responseHasFields(["message"]));

        //after successful logout last token should not be work.
        $notValidToken = $validToken;
        $this->get('auth/logout', ['Authorization' => 'Bearer ' . $notValidToken, 'Accept' => 'application/json']);
        $this->assertResponseStatus(401);
    }

    /**
     * @test
     */
    public function successful_me()
    {
        $user = $this->createUser();
        $validToken = $this->getValidToken($user);
        $this->get('auth/me', ['Authorization' => 'Bearer ' . $validToken, 'Accept' => 'application/json']);
        $res = json_decode($this->response->getContent());

        $this->assertEquals($user->name, $res->name);
        $this->assertEquals($user->email, $res->email);
        $this->assertEquals($user->phone_number, $res->phone_number);
    }

    /**
     * @test
     */
    public function not_successful_me()
    {
        $notValidToken = $this->getNotValidToken();
        $this->get('auth/me', ['Authorization' => 'Bearer ' . $notValidToken, 'Accept' => 'application/json']);

        $this->assertResponseStatus(401);
    }

    /**
     * @test
     */
    public function successful_refresh_token()
    {
        $user = $this->createUser();
        $validToken = $this->getValidToken($user);
        $this->get('auth/refresh', ['Authorization' => 'Bearer ' . $validToken, 'Accept' => 'application/json']);
        $this->assertResponseOk();
        $this->assertTrue($this->responseHasFields(["access_token", "expires_in", "token_type"]));
    }

    /**
     * @test
     */
    public function not_successful_refresh_token()
    {
        $notValidToken = $this->getNotValidToken();
        $this->get('auth/refresh', ['Authorization' => 'Bearer ' . $notValidToken, 'Accept' => 'application/json']);
        $this->assertResponseStatus(401);
    }

    /**
     * Checks that response json string has specific fields or not.
     *
     * @param array $fields
     * @return bool
     */
    private function responseHasFields(array $fields)
    {
        $jsonObject = json_decode($this->response->getContent());

        foreach ($fields as $key => $field) {
            if (!isset($jsonObject->$field) || empty($jsonObject->$field)) {
                return false;
            }
        }

        return true;
    }
}
