<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'question' => fake()->sentence(8) . '?',
            'answer' => fake()->paragraph(),
            'category' => 'general',
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}

