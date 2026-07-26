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
        Schema::create('category_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('global_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feed_item_category_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['include', 'exclude'])->default('include');
            $table->string('language', 8)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_rules');
    }
};
