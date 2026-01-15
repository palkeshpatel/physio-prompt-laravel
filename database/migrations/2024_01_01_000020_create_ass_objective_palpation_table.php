<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ass_objective_palpation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessments_process_id');
            $table->foreign('assessments_process_id', 'fk_obj_palp_process')->references('id')->on('assessments_process')->onDelete('cascade');
            $table->json('tenderness')->nullable();
            $table->string('temperature', 50)->nullable();
            $table->string('swelling', 100)->nullable();
            $table->json('tissue_texture')->nullable();
            $table->string('crepitus', 50)->nullable();
            $table->text('additional_info')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ass_objective_palpation');
    }
};

