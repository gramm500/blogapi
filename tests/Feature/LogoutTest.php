<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesApplication;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use CreatesApplication;
    use DatabaseMigrations;
    use HasFactory;

    public function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public const ROUTE = '/api/logout';

    public function testSuccessfulLogout(): void
    {
        $testUser = User::factory()->create();
        $token = $testUser->createToken('default')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('post', self::ROUTE, [], $headers)->assertStatus(200);

        self::assertEquals(null, $testUser->api_token);
    }

    public function testNoUserFoundLogout(): void
    {
        $testUser = [
            'email' => '1@aea.com',
            'password' => '123',
        ];
        $this->json('post', self::ROUTE, $testUser)
            ->assertStatus(401);
    }

}
