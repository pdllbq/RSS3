<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_embeddings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('feed_item_id')->constrained()->cascadeOnDelete();
            $table->string('model');
            $table->vector('embedding_1024', 1024)->nullable(); // Assuming 1024 dimensions for the embedding vector
            $table->vector('embedding_qwen3_8b_1536', 1536)->nullable();

            $table->timestamps();

            $table->unique(['feed_item_id', 'model']);
        });

        DB::statement('CREATE INDEX item_embeddings_embedding_1024_hnsw_idx
        ON item_embeddings
        USING hnsw (embedding_1024 vector_cosine_ops)');

        DB::statement('CREATE INDEX item_embeddings_embedding_qwen3_8b_1536_hnsw_idx
        ON item_embeddings
        USING hnsw (embedding_qwen3_8b_1536 vector_cosine_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_embeddings');
    }
};
