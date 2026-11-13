<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'category' => Post::CATEGORY_DEVOPS_DEMO,
            'work_type' => Post::WORK_TYPE_LAB_DEMONSTRATION,
            'hook_text' => '$ docker compose up -d --build',
            'excerpt' => fake()->paragraph(),
            'content_blocks' => [
                [
                    'type' => 'markdown_prose',
                    'data' => [
                        'heading' => 'Containerizing the Runtime',
                        'body' => 'Packaging the application inside a deterministic multi-stage Dockerfile.',
                    ],
                ],
            ],
            'code_snippets' => [
                [
                    'title' => 'docker-compose.prod.yml',
                    'language' => 'yaml',
                    'code' => "services:\n  app:\n    restart: always",
                ],
            ],
            'video_url' => null,
            'reading_time_minutes' => 5,
            'is_published' => true,
            'published_at' => now(),
        ];
    }
}

