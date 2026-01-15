<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_subjective_yellow_flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessments_process_id');
            $table->foreign('assessments_process_id', 'fk_sub_yf_process')->references('id')->on('assessments_process')->onDelete('cascade');
            $table->json('yellow_flags')->nullable();
            $table->boolean('yellow_flag_present')->default(false);
            $table->text('yellow_flag_details')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_subjective_yellow_flags');
    }
};

