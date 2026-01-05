<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assessment_type_id')->constrained('assessment_types')->onDelete('cascade');
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->text('clinical_impression')->nullable();
            $table->text('rehab_program')->nullable();
            $table->string('pdf_path', 255)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};



