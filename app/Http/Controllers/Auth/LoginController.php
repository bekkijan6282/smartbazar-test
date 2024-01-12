<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only(['password', 'email']))) {
            return response()->json([
                'message' => 'Credentials did not match',
            ]);
        }

        $user  = $request->user();
        $token = $user->createToken($request->token_name);

        return response()->json([
            'token' => $token->plainTextToken,
        ]);
    }
}
