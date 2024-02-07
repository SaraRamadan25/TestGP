<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Notifications\CustomPasswordResetNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($validatedData['password'] !== $request->input('confirm_password')) {
            throw ValidationException::withMessages([
                'password' => 'The password and confirmation password do not match.',
            ]);
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::create($validatedData);

        $token = $user->createToken('authToken')->plainTextToken;
        $loginResponse = [
            'user' => UserResource::make($user),
            'token' => $token,
        ];
        return response()->json($loginResponse, 201);
    }
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Bad credentials, Try Again',
            ], 422);
        }

        $token = $user->createToken('authToken')->plainTextToken;
        $loginResponse = [
            'user' => UserResource::make($user),
            'token' => $token,
        ];
        return response()->json($loginResponse, 200);
    }
    public function logout(Request $request): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout Successfully',
        ]);
    }
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $resetPasswordToken = str_pad(random_int(1, 9999), 6, '0', STR_PAD_LEFT);

        $userPassReset = PasswordResetToken::where('email', $user->email)->first();

        if (!$userPassReset) {
            PasswordResetToken::create([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        } else {
            $userPassReset->update([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        }

        $user->notify(new CustomPasswordResetNotification($resetPasswordToken));

        return response()->json([
            'message' => 'Password reset token sent to your email',
            'token' => $resetPasswordToken,
        ], 200);
    }
    public function reset(ResetPasswordRequest $request)
    {
        $attributes = $request->validated();

        $user = User::where('email', $attributes['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $resetRequest = PasswordResetToken::where('email', $user->email)->first();

        if (!$resetRequest || $resetRequest->token !== $attributes['token']) {
            return response()->json([
                'message' => 'Invalid token',
            ], 404);
        }
        $user->fill([
            'password' => Hash::make($attributes['password']),
        ]);

        $user->save();
        $user->tokens()->delete();
        $resetRequest->delete();

        $token = $user->createToken('authToken')->plainTextToken;

        $loginResponse = [
            'user' => UserResource::make($user),
            'token' => $token,
        ];
        return response()->success(
            $loginResponse,
            'Password reset successfully',
            201
        );
    }
    public function getUserInfo($username): UserResource|JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $authenticatedUser = auth()->user();

        if ($authenticatedUser->username !== $username) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return new UserResource($authenticatedUser);
    }

}
