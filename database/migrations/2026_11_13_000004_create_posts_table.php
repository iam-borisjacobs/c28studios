<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 48)->index(); // devops_demo, developer_conversation, web_software_dev, devsecops_security, engineering_journey
            $table->string('work_type', 32)->default('lab_demonstration')->index(); // client_production, lab_demonstration
            $table->string('hook_text', 500)->nullable();
            $table->text('excerpt');
            $table->json('content_blocks')->nullable();
            $table->json('code_snippets')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedSmallInteger('reading_time_minutes')->default(5);
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

