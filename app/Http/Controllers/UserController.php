<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserDetailsResource;

class UserController extends Controller
{
    /**
     * Returns logged in user details.
     *
     * @return JsonResponse
     */
    public function loggedInUserDetails(): JsonResponse
    {
        return response()->json(new UserDetailsResource(Auth::user()));
    }
}
