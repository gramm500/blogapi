<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\CreatesApplication;
use Tests\TestCase;

class UserTest extends TestCase
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

    public const ROUTE = '/api/user/';

    public function newData(): array
    {
        return [
            'name' => $this->faker->name,
            'password' => '123',
            'avatar' => UploadedFile::fake()->image('photo1.jpg'),
        ];
    }

    public function testSeeUser(): void
    {
        $testUser = User::factory()->create();
        Sanctum::actingAs($testUser);
        $id = $testUser->id;

        $this->json('get', self::ROUTE . $id)
            ->assertStatus(200);
    }

    public function testSeeUserNoAuth(): void
    {
        $testUser = User::factory()->create();
        $id = $testUser->id;

        $this->json('get', self::ROUTE . $id)
            ->assertStatus(401);
    }

    public function testUpdateUserSuccessfully(): void
    {
        Storage::fake('photos');
        $testUser = User::factory()->create();
        Sanctum::actingAs($testUser);
        $id = $testUser->id;

        $newData = $this->newData();
        $this->json('post', self::ROUTE . $id, $newData)
                ->assertOk();
    }

    public function testUpdateUserNoAuth(): void
    {
        Storage::fake('photos');
        $testUser = User::factory()->create();
        $id = $testUser->id;

        $newData = $this->newData();

        $this->json('post', self::ROUTE . $id, $newData)
            ->assertStatus(401);
    }
}
