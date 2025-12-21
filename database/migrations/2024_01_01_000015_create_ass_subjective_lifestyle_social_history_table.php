<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_subjective_lifestyle_social_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id', 'fk_sub_lsh_assessment')->references('id')->on('assessments')->onDelete('cascade');
            $table->json('job_demands')->nullable();
            $table->string('work_hours', 50)->nullable();
            $table->string('smoking', 50)->nullable();
            $table->string('alcohol', 50)->nullable();
            $table->text('exercise')->nullable();
            $table->text('hobbies')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_subjective_lifestyle_social_history');
    }
};

