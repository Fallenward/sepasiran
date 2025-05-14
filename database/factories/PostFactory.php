<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'excerpt' => $this->faker->text(100),
            'body' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(),
        ];
    }
}
