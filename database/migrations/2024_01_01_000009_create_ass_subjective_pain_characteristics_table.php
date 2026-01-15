<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_subjective_pain_characteristics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessments_process_id');
            $table->foreign('assessments_process_id', 'fk_sub_pc_process')->references('id')->on('assessments_process')->onDelete('cascade');
            $table->string('pain_location', 255)->nullable();
            $table->string('pain_type', 100)->nullable();
            $table->integer('pain_scale')->nullable();
            $table->string('pain_pattern', 100)->nullable();
            $table->text('aggravating_factors')->nullable();
            $table->text('easing_factors')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_subjective_pain_characteristics');
    }
};

