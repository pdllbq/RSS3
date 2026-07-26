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
        Schema::create('feed_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('feed_source_id')->constrained()->cascadeOnDelete();

            $table->string('guid')->nullable();
            $table->string('title');
            $table->string('url');
            $table->string('image_url')->nullable();
            $table->string('author')->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('language', 8)->nullable();

            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('fetched_at')->nullable();
            $table->string('checksum', 64)->nullable()->index();
            $table->json('raw_payload')->nullable();

            $table->foreignId('global_category_id')->nullable();
            $table->boolean('is_category_checked')->default(false);
            $table->boolean('needs_category_check')->default(false); // Нужна  ли ручная проверка

            $table->unsignedBigInteger('ai_request_log_id')->nullable();

            $table->boolean('is_cluster_main')->default(false);

            $table->boolean('is_read')->default(false);

            //feed_item_cluster_id will be created in a separate migration to avoid circular dependency issues with feed_item_clusters table
            $table->float('similarity_score')->nullable();
            $table->boolean('is_similarity_checked')->default(false);

            $table->unique(['feed_source_id', 'guid']);
            $table->unique(['feed_source_id', 'url']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_items');
    }
};
