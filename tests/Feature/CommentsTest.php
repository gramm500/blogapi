<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Comment;
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

class CommentsTest extends TestCase
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

    public function newComment(): array
    {
        return Comment::factory()->create(
            [
                'body' => $this->faker->text(),
                'user_id' => 1,
                'post_id' => 1,
            ]
        )->toArray();
    }

    public function testSeeAllComments(): void
    {
        User::factory()->create();

        $testPost = Post::factory()->create();
        $id = $testPost->id;

        $this->json('get', self::ROUTE . $id . '/comments')
            ->assertStatus(200);
    }

    public function testAddCommentSuccessfully(): void
    {
        User::factory()->create();
        Sanctum::actingAs(
            User::factory()->create()
        );

        $testPost = Post::factory()->create();
        $id = $testPost->id;

        $testComment = $this->newComment();

        $this->json('post', self::ROUTE . $id . '/comments', $testComment)
            ->assertStatus(201);
    }

    public function testAddCommentNoAuth(): void
    {
        User::factory()->create();

        $testPost = Post::factory()->create();
        $id = $testPost->id;

        $testComment = $this->newComment();
        $this->json('post', self::ROUTE . $id . '/comments', $testComment)
            ->assertStatus(401);
    }

    public function testAddCommentWithoutBody(): void
    {
        User::factory()->create();
        Sanctum::actingAs(
            User::factory()->create()
        );
        $testPost = Post::factory()->create();
        $id = $testPost->id;

        $testComment = $this->newComment();
        $testComment['body']= '';
        $this->json('post', self::ROUTE . $id . '/comments', $testComment)
            ->assertStatus(422);
    }
}
