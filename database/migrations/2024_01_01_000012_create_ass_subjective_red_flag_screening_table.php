<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_subjective_red_flag_screening', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessments_process_id');
            $table->foreign('assessments_process_id', 'fk_sub_rfs_process')->references('id')->on('assessments_process')->onDelete('cascade');
            $table->json('red_flags')->nullable();
            $table->boolean('red_flag_present')->default(false);
            $table->text('red_flag_details')->nullable();
            $table->text('additional_info')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_subjective_red_flag_screening');
    }
};

