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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('tier', 32)->index(); // primary, secondary, supporting
            $table->string('tier_badge', 64)->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_outcome', 500);
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->json('deliverables')->nullable();
            $table->json('builder_blocks')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

