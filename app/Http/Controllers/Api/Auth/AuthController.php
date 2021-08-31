<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthController extends Controller
{
    public function register(AuthRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        /** @var User $user */
        $user = User::create($validated);

        $user->assignRole('user');

        $deviceName = $validated['device_name'] ?? 'default';

        $token = $user->createToken($deviceName);

        return response()->json(['token' => $token->plainTextToken]);
    }

    public function login(AuthRequest $request): JsonResponse
    {
        $validator = $request->validated();

        $user = User::whereEmail($validator['email'])->firstOrFail();

        if (!Hash::check($validator['password'], $user->password)) {
            throw new AccessDeniedHttpException();
        }

        return response()->json(['token' => $user->createToken('default')->plainTextToken]);
    }

    public function logout(Request $request): void
    {
        /** @var User $user */
        $user = $request->user();
        $user->tokens()->delete();
    }
}
