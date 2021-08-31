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

class TagTest extends TestCase
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

    public const ROUTE = '/api/tags/';

    public function testSeeAllTags(): void
    {
        $this->json('get', self::ROUTE)->assertStatus(200);
    }

    public function testSeeOneTag(): void
    {
        $tag = Tag::factory()->create();
        $this->json('get', self::ROUTE . $tag->id)->assertStatus(200);
    }

    public function testCreateTagSuccessfully(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $testTag = ['name' => $this->faker->word];

        $this->json('post', self::ROUTE, $testTag)
            ->assertStatus(201);
    }

    public function testCreateTagNoAuth(): void
    {
        $testTag = ['name' => $this->faker->word];

        $this->json('post', self::ROUTE, $testTag)
            ->assertStatus(401);
    }

    public function testCreateTagNoName(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );
        $testTag = [];

        $this->json('post', self::ROUTE, $testTag)
            ->assertStatus(422);
    }

    public function testUpdateTagSuccessfully(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $tag = Tag::factory()->create();
        $updated = ['name' => $this->faker->word()];

        $this->json('put', self::ROUTE . $tag->id, $updated)
            ->assertStatus(200);
    }

    public function testUpdateTagNoAuth(): void
    {
        $tag = Tag::factory()->create();
        $updated = ['name' => $this->faker->word()];

        $this->json('put', self::ROUTE . $tag->id, $updated)
            ->assertStatus(401);
    }

    public function testDeleteTagSuccessfully(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $tag = Tag::factory()->create();

        $this->json('delete', self::ROUTE . $tag->id)
            ->assertOk();
    }

    public function testDeleteTagNoAuth(): void
    {
        $tag = Tag::factory()->create();

        $this->json('delete', self::ROUTE . $tag->id)
            ->assertStatus(401);
    }

    public function testSearchTags(): void
    {
        User::factory()->times(1)->create();
        Tag::factory()->times(5)->create();
        Post::factory()->times(10)->create();

        $tagName = Tag::first()->name;
        Post::first()->tags()->sync([1]);

        $this->call('get', self::ROUTE . 'search', ['q' => $tagName])
            ->assertOk();
    }
}