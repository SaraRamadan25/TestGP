<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Guard;
use App\Models\PasswordResetToken;
use App\Models\Trainer;
use App\Models\User;
use App\Notifications\CustomPasswordResetNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function register(StoreUserRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($validatedData['password'] !== $request->input('confirm_password')) {
            throw ValidationException::withMessages([
                'password' => 'The password and confirmation password do not match.',
            ]);
        }

        $validatedData['password'] = Hash::make($validatedData['password']);

        unset($validatedData['confirm_password']);

        $user = User::create($validatedData);

        return $this->generateTokenResponse($user, 201);
    }
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'message' => 'Bad credentials, Try Again',
            ], 422);
        }

        return $this->generateTokenResponse($user, 200);
    }
    private function generateTokenResponse(User $user, int $statusCode): JsonResponse
    {
        $token = $user->createToken('authToken')->plainTextToken;

        $loginResponse = [
            'user' => UserResource::make($user),
            'token' => $token,
        ];

        return response()->json($loginResponse, $statusCode);
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
    public function reset(ResetPasswordRequest $request): JsonResponse
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
        ])->save();

        $user->tokens()->delete();
        $resetRequest->delete();

        $token = $user->createToken('authToken')->plainTextToken;

        $loginResponse = [
            'user' => UserResource::make($user),
            'token' => $token,
        ];

        return response()->json([
            'message' => 'Password reset successfully',
            'data' => $loginResponse,
        ], 201);
    }

    public function logout(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        else {
            $user->tokens()->delete();
        }

        return response()->json([
            'message' => 'Logged Out Successfully',
        ]);
    }

}
