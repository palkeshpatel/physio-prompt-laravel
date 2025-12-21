<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_objective_neurological_examination', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id', 'fk_obj_neuro_assessment')->references('id')->on('assessments')->onDelete('cascade');
            $table->json('sensation')->nullable();
            $table->json('reflexes')->nullable();
            $table->json('myotomes')->nullable();
            $table->json('neural_tension')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_objective_neurological_examination');
    }
};

