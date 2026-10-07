<?php

namespace Tests\Feature;

use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedItemClick;
use App\Models\Feed\FeedSource;
use App\Models\Feed\GlobalCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutboundControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_records_click_and_redirects_to_external_url(): void
    {
        $source = FeedSource::create([
            'custom_title' => 'Test Source',
            'url' => 'https://example.com/rss',
            'language' => 'ru',
        ]);

        $category = GlobalCategory::create([
            'name' => 'News',
            'slug' => 'news',
            'language' => 'ru',
        ]);

        $item = FeedItem::create([
            'feed_source_id' => $source->id,
            'title' => 'Important News',
            'url' => 'https://news.example.com/article-123',
            'language' => 'ru',
            'global_category_id' => $category->id,
            'is_category_checked' => true,
            'needs_category_check' => false,
            'is_similarity_checked' => true,
            'is_cluster_main' => true,
        ]);

        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '192.168.1.100',
            'HTTP_USER_AGENT' => 'TestBrowser/1.0',
            'HTTP_REFERER' => 'https://google.com',
        ])->get("/go/{$item->id}");

        $response->assertRedirect('https://news.example.com/article-123');

        $this->assertDatabaseHas('feed_item_clicks', [
            'feed_item_id' => $item->id,
            'ip_address' => '192.168.1.100',
            'user_agent' => 'TestBrowser/1.0',
            'referer' => 'https://google.com',
        ]);

        $this->assertEquals(1, $item->clicks()->count());
    }

    public function test_it_returns_404_for_unknown_item(): void
    {
        $response = $this->get('/go/999999');

        $response->assertNotFound();
    }

    public function test_it_renders_intermediate_page_when_configured(): void
    {
        config(['feed.show_intermediate_page' => true]);

        $source = FeedSource::create([
            'custom_title' => 'Test Source',
            'url' => 'https://example.com/rss',
            'language' => 'ru',
        ]);

        $item = FeedItem::create([
            'feed_source_id' => $source->id,
            'title' => 'Intermediate News Title',
            'url' => 'https://news.example.com/target-article',
            'language' => 'ru',
            'is_category_checked' => true,
            'needs_category_check' => false,
            'is_similarity_checked' => true,
            'is_cluster_main' => true,
        ]);

        $response = $this->get("/go/{$item->id}");

        $response->assertOk();
        $response->assertSee('Intermediate News Title');
        $response->assertSee('Переход к источнику');
        $response->assertSee('https://news.example.com/target-article');

        $this->assertDatabaseHas('feed_item_clicks', [
            'feed_item_id' => $item->id,
        ]);
    }

    public function test_rss_feed_contains_outbound_links(): void
    {
        $source = FeedSource::create([
            'custom_title' => 'Test Source',
            'url' => 'https://example.com/rss',
            'language' => 'ru',
        ]);

        $category = GlobalCategory::create([
            'name' => 'News',
            'slug' => 'news',
            'language' => 'ru',
        ]);

        $item = FeedItem::create([
            'feed_source_id' => $source->id,
            'guid' => 'guid-unique-123',
            'title' => 'RSS Outbound News',
            'url' => 'https://external-news.com/item-1',
            'language' => 'ru',
            'published_at' => now(),
            'global_category_id' => $category->id,
            'is_category_checked' => true,
            'needs_category_check' => false,
            'is_similarity_checked' => true,
            'is_cluster_main' => true,
        ]);

        $response = $this->get('/ru/rss');

        $response->assertOk();
        $response->assertSee("<link>http://localhost/go/{$item->id}</link>", false);
        $response->assertSee('<guid>guid-unique-123</guid>', false);
    }
}
