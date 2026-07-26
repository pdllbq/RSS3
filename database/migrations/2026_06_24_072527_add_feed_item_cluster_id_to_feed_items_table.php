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
        Schema::table('feed_items', function (Blueprint $table) {
            $table->foreignId('feed_item_cluster_id')
                ->nullable()
                ->after('is_read')
                ->constrained('feed_item_clusters')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feed_items', function (Blueprint $table) {
            $table->dropForeign(['feed_item_cluster_id']);
            $table->dropColumn('feed_item_cluster_id');
        });
    }
};
