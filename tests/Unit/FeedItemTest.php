<?php

namespace Tests\Unit;

use App\Models\Feed\FeedItem;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FeedItemTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('feed_items');

        Schema::create('feed_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feed_item_cluster_id')->nullable();
            $table->boolean('is_cluster_main')->default(false);
            $table->timestamps();
        });
    }

    public function test_it_marks_the_feed_item_as_cluster_main_and_resets_the_previous_one(): void
    {
        $firstItem = FeedItem::create([
            'feed_item_cluster_id' => 10,
            'is_cluster_main' => true,
        ]);

        $secondItem = FeedItem::create([
            'feed_item_cluster_id' => 10,
            'is_cluster_main' => false,
        ]);

        $secondItem->markAsClusterMain();

        $firstItem->refresh();
        $secondItem->refresh();

        $this->assertFalse($firstItem->is_cluster_main);
        $this->assertTrue($secondItem->is_cluster_main);
    }
}
