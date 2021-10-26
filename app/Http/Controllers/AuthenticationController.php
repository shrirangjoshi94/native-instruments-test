<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\{JsonResponse, Response};
use App\Http\Requests\Auth\GenerateTokenRequest;

class AuthenticationController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param GenerateTokenRequest $request
     * @return JsonResponse
     */
    public function generateAuthToken(GenerateTokenRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['token' => $user->createToken('access_token')->plainTextToken]);
        }

        return response()->json(['message' => __('auth.password')], Response::HTTP_UNAUTHORIZED);
    }
}
