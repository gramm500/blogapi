<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Exception;
use Illuminate\Database\Seeder;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $maxNumberOfPosts = Tag::count();
        $commentFactory = Comment::factory()->state(
            function (array $attributes, Post $post) {
                return ['post_id' => $post->id];
            }
        );
        Post::factory()
            ->has(
                $commentFactory
            )->count(10)
            ->create();
        $posts = Post::all();
        foreach ($posts as $post) {
            $post->tags()->sync([random_int(1, $maxNumberOfPosts), random_int(1, $maxNumberOfPosts)]);
        }
    }
}
