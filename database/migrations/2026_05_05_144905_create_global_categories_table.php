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
        Schema::create('global_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('global_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('language', 8)->nullable();
            $table->timestamps();
        });

        Schema::table('feed_items', function (Blueprint $table) {
            $table->foreign('global_category_id')->references('id')->on('global_categories')->nullOnDelete();
        });

        Schema::create('feed_item_global_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('global_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['feed_item_id', 'global_category_id']);
        });

        Schema::create('feed_item_category_global_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_item_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('global_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['feed_item_category_id', 'global_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_item_category_global_category');
        Schema::dropIfExists('feed_item_global_category');

        Schema::table('feed_items', function (Blueprint $table) {
            $table->dropForeign(['global_category_id']);
        });

        Schema::dropIfExists('global_categories');
    }
};
