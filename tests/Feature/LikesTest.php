<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\CreatesApplication;
use Tests\TestCase;

class LikesTest extends TestCase
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

    public const ROUTE = '/api/posts/';

    public function testAddLike(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $post = Post::factory()->create();
        $id = $post->id;

        $this->json('post', self::ROUTE . $id . '/likes')
            ->assertStatus(200);
    }

    public function testAddLikeNoAuth(): void
    {
        User::factory()->create();

        $post = Post::factory()->create();
        $id = $post->id;

        $this->json('post', self::ROUTE . $id . '/likes')
            ->assertStatus(401);
    }

    public function testViewLikes(): void
    {
        User::factory()->create();

        $post = Post::factory()->create();
        $id = $post->id;

        $this->json('get', self::ROUTE . $id . '/likes')
            ->assertStatus(200);
    }
}
