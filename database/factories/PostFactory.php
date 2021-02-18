<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
    	return [
    	    'title' => $this->faker->name,
            'content' => $this->faker->text,
            'author' => $this->faker->name,
            'category' => $this->faker->numberBetween($min = 1, $max = 3),
            'status' => $this->faker->numberBetween($min = 1, $max = 2),
            'created_at' => \Carbon\Carbon::now(),    
            'updated_at' => \Carbon\Carbon::now(),    
    	];
    }
}
