<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_objective_range_of_motion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id', 'fk_obj_rom_assessment')->references('id')->on('assessments')->onDelete('cascade');
            $table->json('active_rom')->nullable();
            $table->json('passive_rom')->nullable();
            $table->boolean('pain_during_arom')->default(false);
            $table->string('pain_location_arom', 255)->nullable();
            $table->string('end_feel', 100)->nullable();
            $table->text('comparison_other_side')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_objective_range_of_motion');
    }
};

