<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    protected $model = SiteSetting::class;

    public function definition(): array
    {
        return [
            'key' => 'custom.' . fake()->unique()->slug(2),
            'group' => 'general',
            'type' => SiteSetting::TYPE_TEXT,
            'value' => fake()->sentence(),
            'json_value' => null,
            'description' => fake()->sentence(),
        ];
    }
}

