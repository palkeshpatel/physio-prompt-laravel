<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 50)->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->integer('free_assessments_limit')->default(0);
            $table->boolean('unlimited_assessments')->default(false);
            $table->boolean('ad_free')->default(false);
            $table->boolean('pdf_download')->default(false);
            $table->boolean('ai_impression')->default(false);
            $table->boolean('ai_rehab_program')->default(false);
            $table->boolean('reassessment_enabled')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};



