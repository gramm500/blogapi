<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @return UserResource
     */
    public function update(UserRequest $request): UserResource
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        if (array_key_exists('avatar', $validated)) {
            $user->addMedia($validated['avatar'])->toMediaCollection('avatar');
            unset($validated['avatar']);
        }

        $user->update($validated);

        return new UserResource($user);
    }
}

