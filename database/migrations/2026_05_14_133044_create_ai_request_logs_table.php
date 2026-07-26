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
        Schema::create('ai_request_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ai_model_id')->constrained()->onDelete('cascade');
            $table->foreignId('ai_provider_id')->constrained()->onDelete('cascade');

            $table->string('status')->default('pending'); // e.g., 'pending', 'success', 'error'

            $table->string('task')->nullable();
            $table->longText('prompt')->nullable();
            $table->longText('response')->nullable();

            $table->unsignedInteger('tokens_input')->nullable();
            $table->unsignedInteger('tokens_output')->nullable();
            $table->unsignedInteger('tokens_total')->nullable();
            $table->decimal('cost', 10, 6)->nullable();

            $table->text('error_message')->nullable();

            $table->jsonb('request_payload')->nullable();
            $table->jsonb('response_payload')->nullable();
            $table->jsonb('messages')->nullable(); // For chat-based interactions, store the message history
            $table->vector('embedding_1024', 1024)->nullable();

            $table->boolean('is_valid_json')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_request_logs');
    }
};
