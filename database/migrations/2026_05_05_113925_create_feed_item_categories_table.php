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
        Schema::create('feed_item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('term')->unique();
            $table->string('scheme')->nullable();
            $table->string('label')->nullable();
            $table->string('type')->nullable();
            $table->string('language', 8)->nullable();
            $table->timestamps();

            $table->index('term');
        });

        Schema::create('feed_item_category_feed_source', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_item_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feed_source_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['feed_item_category_id', 'feed_source_id']);
        });

        Schema::create('feed_item_category_feed_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_item_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feed_item_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['feed_item_category_id', 'feed_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_item_category_feed_item');
        Schema::dropIfExists('feed_item_category_feed_source');
        Schema::dropIfExists('feed_item_categories');
    }
};
