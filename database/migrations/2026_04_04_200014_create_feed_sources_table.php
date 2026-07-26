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
        Schema::create('feed_sources', function (Blueprint $table) {
            $table->id();

            $table->string('source_title')->nullable();
            $table->string('custom_title');
            $table->string('url')->unique();
            $table->string('site_url')->nullable();
            $table->text('source_description')->nullable();
            $table->string('language', 8)->nullable();
            $table->string('source_link')->nullable();
            $table->string('source_permalink')->nullable();
            $table->timestamp('source_date')->nullable();
            $table->string('source_author')->nullable();
            $table->json('source_authors')->nullable();
            $table->string('source_image_url')->nullable();
            $table->string('source_favicon')->nullable();
            $table->integer('source_item_quantity')->nullable();
            $table->json('source_raw_data')->nullable();

            $table->enum('added_by', ['user', 'system', 'admin'])->default('system');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamp('last_fetched_at')->nullable();
            $table->timestamp('last_success_at')->nullable();
            $table->text('last_error')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_sources');
    }
};
