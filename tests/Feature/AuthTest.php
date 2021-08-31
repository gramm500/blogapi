<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\CreatesApplication;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use CreatesApplication;
    use DatabaseMigrations;
    use HasFactory;

    public const ROUTE = '/api/register';

    public function testUser(): array
    {
        return [
            'email' => $this->faker->email,
            'password' => $this->faker->password,
        ];
    }


    public function permissionsSeed(): void
    {
        $userPermission = Permission::create(['name' => 'write']);
        Role::create(['name' => 'user'])->givePermissionTo($userPermission);
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testRegistersSuccessfully(): void
    {
        $this->permissionsSeed();

        $this->json('post', self::ROUTE, $this->testUser())
            ->assertOk();
    }

    public function testUserRegisterNoPassword(): void
    {
        $this->permissionsSeed();

        $testUser = $this->testUser();
        $testUser['password'] = "";

        $this->json('post', self::ROUTE, $testUser)
            ->assertStatus(422);
    }

    public function testUserRegisterNoEmail(): void
    {
        $this->permissionsSeed();

        $testUser = $this->testUser();
        $testUser['email'] = "";

        $this->json('post', self::ROUTE, $testUser)
            ->assertStatus(422);
    }
}
