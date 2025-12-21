<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_subjective_chief_complaint', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id', 'fk_sub_cc_assessment')->references('id')->on('assessments')->onDelete('cascade');
            $table->text('chief_complaint')->nullable();
            $table->enum('onset', ['Sudden', 'Gradual', 'After a specific incident'])->nullable();
            $table->dateTime('onset_date')->nullable();
            $table->json('symptoms')->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_subjective_chief_complaint');
    }
};

