<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_objective_muscle_strength', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id', 'fk_obj_ms_assessment')->references('id')->on('assessments')->onDelete('cascade');
            $table->json('mmt_scores')->nullable();
            $table->string('core_activation', 50)->nullable();
            $table->boolean('pain_on_resistance')->default(false);
            $table->string('pain_movement', 255)->nullable();
            $table->json('functional_tests')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_objective_muscle_strength');
    }
};

