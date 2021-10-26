<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;

class UserControllerTest extends TestCase
{
    /**
     * Test the API to get the logged in user details.
     */
    public function testGetLoggedInUserDetails()
    {
        $user = User::where('email', 'mac94@moen.com')->first();
        $token = $user->createToken('access_token')->plainTextToken;
        $response = $this->withHeaders(['authorization' => "Bearer $token"])->get('/api/user');
        $response->assertStatus(Response::HTTP_OK);
    }
}
