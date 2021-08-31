<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\CreatesApplication;
use Tests\TestCase;


class PostTest extends TestCase
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

    public function testPost(): array
    {
        return [
            'title' => $this->faker->text('25'),
            'content' => $this->faker->text(),
            'tags' => [1, 2, 3],
            'user_id' => 1,
        ];
    }

    public function testIndexAllPosts(): void
    {
        $this->json('get', self::ROUTE)->assertStatus(200);
    }

    public function testSuccessfulCreationOfPost(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );
        Tag::factory()->times(3)->create();

        $testPost = $this->testPost();

        $this->json('post', self::ROUTE, $testPost)
            ->assertStatus(201);
    }

    public function testCreatePostTooManyTags(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Tag::factory()->times(20)->create();

        $testPost = $this->testPost();
        $testPost['tags'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];

        $this->json('post', self::ROUTE, $testPost)
            ->assertJson(['errors' => true])
            ->assertStatus(422);
    }

    public function testCreatePostNoAuth(): void
    {
        User::factory()->create();

        $testPost = $this->testPost();

        $this->json('post', self::ROUTE, $testPost)
            ->assertStatus(401);
    }

    public function testCreatePostWithoutTitle(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $testPost = $this->testPost();
        $testPost['title'] = '';

        $this->json('post', self::ROUTE, $testPost)
            ->assertStatus(422);
    }

    public function testSuccessfulUpdatePost(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Tag::factory()->times(2)->create();

        $post = Post::factory()->create();
        $updated = [
            'title' => 'test title',
            'content' => 'test content',
            'tags' => [1, 2],
        ];
        $id = $post->id;

        $this->json('put', self::ROUTE . $id, $updated)
            ->assertStatus(200);
    }

    public function testUpdatePostNoAuth(): void
    {
        User::factory()->create();
        $post = Post::factory()->create();

        $updated = [
            'title' => 'test title',
            'content' => 'test content'
        ];

        $id = $post->id;
        $this->json('put', self::ROUTE . $id, $updated)
            ->assertStatus(401);
    }

    public function testSuccessfulDeletePost(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $post = Post::factory()->create();
        $id = $post->id;

        $this->json('delete', self::ROUTE . $id)
            ->assertOk();
        
    }

    public function testDeletePostNoAuth(): void
    {
        User::factory()->create();

        $post = Post::factory()->create();
        $id = $post->id;

        $this->json('delete', self::ROUTE . $id)
            ->assertStatus(401);
    }

    public function testWrongUserIdDeletePost(): void
    {
        User::factory()->create();
        $post = Post::factory()->create();
        $id = $post->id;

        Sanctum::actingAs(
            User::factory()->create()
        );

        $this->json('delete', self::ROUTE . $id)
            ->assertStatus(403);
    }

    public function testSearchPost(): void
    {
        User::factory()->create();
        $post = Post::factory()->create();

        $this->call('get', self::ROUTE . 'search', ['q' => $post->title])
            ->assertOk();
    }
}
