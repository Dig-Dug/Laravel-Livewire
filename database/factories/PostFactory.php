<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Post;


class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
protected $model = Post::class;

    public function definition()
    {
        $title = $this->faker->sentence(6, true);
        return [
              'title'=> $title,
        'body'=> $this->faker->paragraph(3, true),
        'image'=> null,
        ];
    }
}
