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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('work_type', 32)->index(); // client_production, lab_demonstration
            $table->string('client_or_context')->nullable();
            $table->text('problem_summary');
            $table->text('solution_summary');
            $table->json('outcome_metrics')->nullable();
            $table->json('tech_stack')->nullable();
            $table->json('architecture_steps')->nullable();
            $table->json('code_artifacts')->nullable();
            $table->json('builder_blocks')->nullable();
            $table->string('repo_url')->nullable();
            $table->string('live_url')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

