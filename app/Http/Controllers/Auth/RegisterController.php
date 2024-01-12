<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $input             = $request->validated();
        $role_id = Role::where('name', $input['role'])->first()->id;
        $input['password'] = bcrypt($input['password']);
        $input['role_id'] = $role_id;
        $user              = User::create($input);
        $token             = $user->createToken($request->token_name);

        return response()->json([
            'token' => $token->plainTextToken,
        ]);
    }
}
