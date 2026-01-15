<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_subjective_functional_limitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessments_process_id');
            $table->foreign('assessments_process_id', 'fk_sub_fl_process')->references('id')->on('assessments_process')->onDelete('cascade');
            $table->text('daily_activities')->nullable();
            $table->text('work_activities')->nullable();
            $table->text('recreational_activities')->nullable();
            $table->text('sleep_disturbance')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_subjective_functional_limitations');
    }
};

