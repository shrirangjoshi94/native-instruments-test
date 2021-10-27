<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Http\Response;

class AuthenticationControllerTest extends TestCase
{
    /**
     * Test the generate login token method.
     */
    public function testGenerateAuthToken()
    {
        $request = [
            'email' => 'mac94@moen.com',
            'password' => 'secret',
        ];

        $response = $this->postJson('api/auth', $request);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Test the generate login token method when credentials are wrong.
     */
    public function testGenerateAuthTokenInvalidCredentials()
    {
        $request = [
            'email' => 'mac94@moen1.com',
            'password' => 'secret',
        ];

        $response = $this->postJson('api/auth', $request);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
