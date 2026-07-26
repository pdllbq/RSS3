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
        Schema::create('ai_prompt_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('type'); // e.g., 'chat', 'completion', etc.
            $table->string('task')->nullable(); // e.g., 'summarization', 'translation', etc.

            $table->text("system_prompt")->nullable(); // For chat-based templates, the system prompt
            $table->text("user_prompt")->nullable(); // The user prompt template

            $table->jsonb('model_variables')->nullable(); // Variables that can be used in the prompt templates, stored as JSON
            $table->jsonb('config')->nullable();

            $table->text('version')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_prompt_templates');
    }
};
