<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_subjective_history_present_condition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id', 'fk_sub_hpc_assessment')->references('id')->on('assessments')->onDelete('cascade');
            $table->string('duration', 100)->nullable();
            $table->string('progression', 100)->nullable();
            $table->text('previous_episodes')->nullable();
            $table->text('mechanism_injury')->nullable();
            $table->text('initial_treatment')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_subjective_history_present_condition');
    }
};

