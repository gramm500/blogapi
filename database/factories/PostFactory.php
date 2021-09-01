<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->text('25');
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'likes' => $this->faker->randomDigit,
            'content' => $this->faker->text(),
            'user_id' => 1,
        ];
    }
}
