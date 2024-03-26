<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            $newUser['name'] = $data['email'];
            $newUser['email'] = $data['email'];
            $newUser['password'] = Hash::make($data['password']);
            $user = User::create($newUser);
        }

        $token = $user->createToken(
            name: 'authToken',
            expiresAt: now()->addMinutes(config('sanctum.expiration'))
        );

        return response()->json([
            'message' => __('message.success_login'),
            'data' => [
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ]
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->update(['expires_at' => now()]);
        return response()->json([
            'message' => __('message.success_log_out')
        ], Response::HTTP_OK);
    }
}
