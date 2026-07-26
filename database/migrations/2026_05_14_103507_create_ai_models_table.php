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
        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ai_provider_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('provider_model_id'); // The model ID as defined by the AI provider
            $table->string('type'); // e.g., 'chat', 'completion', etc.
            $table->decimal('price_input', 10, 6)->nullable(); // Price per token for 1M input tokens
            $table->decimal('price_output', 10, 6)->nullable(); // Price per token for 1M output tokens
            $table->unsignedInteger('context_window')->nullable(); // Maximum context window size for the model
            $table->unsignedInteger('embedding_dimensions')->nullable(); // If the model supports embeddings, the dimensionality of the embedding vectors
            $table->boolean('is_active')->default(true); // Whether the model is active and available for use
            $table->json('config')->nullable(); // Additional configuration for the model, such as supported features

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_models');
    }
};
