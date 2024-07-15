<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Guard;
use App\Models\PasswordResetToken;
use App\Models\Trainer;
use App\Models\User;
use App\Notifications\CustomPasswordResetNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    /*
    public function getUserInfo($username): UserResource|JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $authenticatedUser = User::where('username', $username)->first();

        if (!$authenticatedUser || auth()->user()->username !== $username) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return new UserResource($authenticatedUser);
    }
    */
}
