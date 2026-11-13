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
        Schema::create('pricing_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tier_badge', 100)->nullable();
            $table->string('billing_mode', 32)->index(); // sprint_fixed, monthly_retainer, custom
            $table->unsignedInteger('price_amount')->nullable();
            $table->string('price_label', 64);
            $table->string('billing_period', 100)->nullable();
            $table->text('target_audience');
            $table->string('features_heading')->nullable();
            $table->json('features')->nullable();
            $table->string('cta_label', 100);
            $table->string('cta_url')->nullable();
            $table->boolean('is_highlighted')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_tiers');
    }
};

