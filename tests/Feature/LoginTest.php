<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesApplication;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use CreatesApplication;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public const ROUTE = '/api/login';

    public function userAuth(): array
    {
        $user = User::factory()->create();

        return [
            'email' => $user->email,
            'password' => '123',
        ];
    }

    public function testSuccessfulLogin(): void
    {
        $userAuth = $this->userAuth();

        $this->json('post', self::ROUTE, $userAuth)
            ->assertOk();
    }

    public function testWrongPasswordLogin(): void
    {
        $userAuth = $this->userAuth();
        $userAuth['password'] = '456';

        $this->json('post', self::ROUTE, $userAuth)
            ->assertStatus(403);
    }

    public function testNoPasswordLogin(): void
    {
        $userAuth = $this->userAuth();
        $userAuth['password'] = '';

        $this->json('post', self::ROUTE, $userAuth)
            ->assertStatus(422);
    }

    public function testWrongEmailLogin(): void
    {
        $userAuth = $this->userAuth();
        $userAuth['email'] = '1@dsdsd.com';

        $this->json('post', self::ROUTE, $userAuth)
            ->assertStatus(404);
    }

    public function testNoEmailLogin(): void
    {
        $userAuth = $this->userAuth();
        $userAuth['email'] = '';

        $this->json('post', self::ROUTE, $userAuth)
            ->assertStatus(422);
    }

}
