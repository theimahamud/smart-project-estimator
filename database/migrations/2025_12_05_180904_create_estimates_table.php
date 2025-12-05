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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('project_name');
            $table->text('raw_requirements');
            $table->json('parsed_features')->nullable();
            $table->foreignId('region_id')->nullable()->constrained();
            $table->json('selected_technologies')->nullable();
            $table->integer('team_size')->default(1);
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->integer('estimated_days')->nullable();
            $table->string('complexity_level')->nullable();
            $table->json('team_composition')->nullable();
            $table->json('ai_response')->nullable();
            $table->string('status')->default('draft');
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
