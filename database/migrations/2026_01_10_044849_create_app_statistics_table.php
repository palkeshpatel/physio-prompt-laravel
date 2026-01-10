<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_statistics', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['patients', 'clinics', 'countries', 'years'])->unique();
            $table->string('icon', 100)->nullable(); // Icon name from lucide-react
            $table->string('title', 100);
            $table->string('count', 50); // e.g., "2,500+", "50+", "12", "10+"
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_statistics');
    }
};
