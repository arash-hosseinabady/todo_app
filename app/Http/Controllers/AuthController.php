<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $data = $request->all();
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            $newUser['name'] = $data['name'];
            $newUser['email'] = $data['email'];
            $newUser['password'] = Hash::make($data['password']);
            User::create($newUser);
        } else {
            throw ValidationException::withMessages([
                'authentication' => __('auth.incorrect_password'),
            ]);
        }

        return response()->json([
            'message' => __('auth.success_register'),
            'data' => [
                'name' => $data['name'],
                'email' => $data['email'],
            ]
        ], Response::HTTP_OK);
    }

    public function login(AuthLoginRequest $request)
    {
        $data = $request->all();
        $user = User::when(isset($data['username']), function ($query) use ($data) {
            $query->where('username', $data['username']);
        })
            ->when(isset($data['email']), function ($query) use ($data) {
                $query->where('email', $data['email']);
            })
            ->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'authentication' => __('auth.incorrect_credential'),
            ]);
        }

        $token = $user->createToken(
            name: 'authToken',
            expiresAt: now()->addMinutes(config('sanctum.expiration'))
        );

        return response()->json([
            'message' => __('auth.success_login'),
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
            'message' => __('auth.success_log_out')
        ], Response::HTTP_OK);
    }

    public function notification(Request $request)
    {
        $msg = __('msg.bad_request');
        $data = null;
        $unread = $request->all()['unread'] ?? 0;

        if (in_array($unread, [0, 1])) {
            $msg = __('auth.unread_notify');
            $data = $unread ? auth()->user()->unreadNotifications : auth()->user()->notifications;
        }

        return response()->json([
            'message' => $msg,
            'data' => $data
        ], Response::HTTP_OK);
    }

    public function notificationMarkAsRead(Request $request)
    {
        $id = $request->all()['id'] ?? null;
        $msg = __('msg.bad_request');

        if ($id && ($notification = auth()->user()->notifications->find($id))) {
            $notification->markAsRead();
            $msg = __('msg.notification_mark_as_read');
        } elseif (is_null($id)) {
            auth()->user()->unreadNotifications->markAsRead();
            $msg = __('msg.notification_mark_as_read');
        }

        return response()->json([
            'message' => $msg,
            'data' => null
        ], Response::HTTP_OK);
    }
}
