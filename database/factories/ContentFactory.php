<?php

namespace Database\Factories;

use App\Enums\ContentType;
use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(ContentType::cases()),
            'title' => fake()->sentence(3),
            'payload' => [
                'image' => fake()->imageUrl(800, 400, 'banners'),
                'link' => fake()->url(),
                'text' => fake()->paragraph(),
            ],
            'status' => fake()->randomElement(ContentStatus::cases()),
            'order' => fake()->randomFloat(2, 1, 1000),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
