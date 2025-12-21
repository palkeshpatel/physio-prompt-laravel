<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_subjective_medical_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id', 'fk_sub_mh_assessment')->references('id')->on('assessments')->onDelete('cascade');
            $table->text('past_medical_history')->nullable();
            $table->text('surgeries')->nullable();
            $table->text('medications')->nullable();
            $table->text('allergies')->nullable();
            $table->text('family_history')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_subjective_medical_history');
    }
};

