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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('version')->default('1.0');
            $table->string('status');
            $table->text('requirements');
            $table->string('requirements_quality');
            $table->string('quality_level');
            $table->string('team_seniority');
            $table->string('confidence_level')->nullable();
            $table->decimal('total_hours', 10, 2)->nullable();
            $table->decimal('min_hours', 10, 2)->nullable();
            $table->decimal('max_hours', 10, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->decimal('min_cost', 12, 2)->nullable();
            $table->decimal('max_cost', 12, 2)->nullable();
            $table->json('breakdown')->nullable();
            $table->json('assumptions')->nullable();
            $table->json('risks')->nullable();
            $table->json('recommendations')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
