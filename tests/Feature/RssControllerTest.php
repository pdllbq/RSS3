<?php

namespace Tests\Feature;

use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedSource;
use App\Models\Feed\GlobalCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RssControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_rss_for_all_displayable_items_in_language(): void
    {
        $source = FeedSource::create([
            'custom_title' => 'Source',
            'url' => 'https://example.com/feed.xml',
            'language' => 'ru',
        ]);

        $category = GlobalCategory::create([
            'name' => 'News',
            'slug' => 'news',
            'language' => 'ru',
        ]);

        $olderItem = $this->createDisplayableItem($source, $category, 'ru', 'Older item', now()->subHour());
        $newerItem = $this->createDisplayableItem($source, $category, 'ru', 'Newer item', now());
        $this->createDisplayableItem($source, $category, 'lv', 'Wrong language', now()->addHour());
        $this->createHiddenItem($source, $category, 'ru', 'Hidden item');

        $response = $this->get('/ru/rss');

        $response
            ->assertOk()
            ->assertHeader('content-type', 'application/rss+xml; charset=UTF-8')
            ->assertSee('<rss version="2.0"', false)
            ->assertSee($newerItem->title)
            ->assertSee($olderItem->title)
            ->assertDontSee('Wrong language')
            ->assertDontSee('Hidden item');

        $content = $response->getContent();

        $this->assertLessThan(
            strpos($content, $olderItem->title),
            strpos($content, $newerItem->title),
        );
    }

    public function test_it_returns_rss_for_category_and_descendants(): void
    {
        $source = FeedSource::create([
            'custom_title' => 'Source',
            'url' => 'https://example.com/feed.xml',
            'language' => 'ru',
        ]);

        $category = GlobalCategory::create([
            'name' => 'News',
            'slug' => 'news',
            'language' => 'ru',
        ]);

        $childCategory = GlobalCategory::create([
            'parent_id' => $category->id,
            'name' => 'Latvia',
            'slug' => 'news/latvia',
            'language' => 'ru',
        ]);

        $siblingCategory = GlobalCategory::create([
            'name' => 'Sport',
            'slug' => 'sport',
            'language' => 'ru',
        ]);

        $parentItem = $this->createDisplayableItem($source, $category, 'ru', 'Parent item', now());
        $childItem = $this->createDisplayableItem($source, $childCategory, 'ru', 'Child item', now()->subMinute());
        $this->createDisplayableItem($source, $siblingCategory, 'ru', 'Sibling item', now()->addMinute());

        $response = $this->get('/ru/rss/news');

        $response
            ->assertOk()
            ->assertSee($parentItem->title)
            ->assertSee($childItem->title)
            ->assertDontSee('Sibling item');
    }

    public function test_it_returns_rss_for_subcategory_without_siblings_or_parent_only_items(): void
    {
        $source = FeedSource::create([
            'custom_title' => 'Source',
            'url' => 'https://example.com/feed.xml',
            'language' => 'ru',
        ]);

        $category = GlobalCategory::create([
            'name' => 'News',
            'slug' => 'news',
            'language' => 'ru',
        ]);

        $childCategory = GlobalCategory::create([
            'parent_id' => $category->id,
            'name' => 'Latvia',
            'slug' => 'news/latvia',
            'language' => 'ru',
        ]);

        $siblingCategory = GlobalCategory::create([
            'parent_id' => $category->id,
            'name' => 'World',
            'slug' => 'news/world',
            'language' => 'ru',
        ]);

        $childItem = $this->createDisplayableItem($source, $childCategory, 'ru', 'Child item', now());
        $this->createDisplayableItem($source, $category, 'ru', 'Parent item', now()->addMinute());
        $this->createDisplayableItem($source, $siblingCategory, 'ru', 'Sibling item', now()->subMinute());

        $response = $this->get('/ru/rss/news/latvia');

        $response
            ->assertOk()
            ->assertSee($childItem->title)
            ->assertDontSee('Parent item')
            ->assertDontSee('Sibling item');
    }

    public function test_it_does_not_return_category_for_another_language(): void
    {
        GlobalCategory::create([
            'name' => 'News',
            'slug' => 'news',
            'language' => 'lv',
        ]);

        $this->get('/ru/rss/news')
            ->assertNotFound();
    }

    private function createDisplayableItem(
        FeedSource $source,
        GlobalCategory $category,
        string $language,
        string $title,
        mixed $publishedAt,
    ): FeedItem {
        return FeedItem::create([
            'feed_source_id' => $source->id,
            'guid' => 'guid-'.str($title)->slug(),
            'title' => $title,
            'url' => 'https://example.com/'.str($title)->slug(),
            'description' => $title.' description',
            'language' => $language,
            'published_at' => $publishedAt,
            'global_category_id' => $category->id,
            'is_category_checked' => true,
            'needs_category_check' => false,
            'is_similarity_checked' => true,
            'is_cluster_main' => true,
        ]);
    }

    private function createHiddenItem(
        FeedSource $source,
        GlobalCategory $category,
        string $language,
        string $title,
    ): FeedItem {
        return FeedItem::create([
            'feed_source_id' => $source->id,
            'title' => $title,
            'url' => 'https://example.com/'.str($title)->slug(),
            'language' => $language,
            'published_at' => now(),
            'global_category_id' => $category->id,
            'is_category_checked' => false,
            'needs_category_check' => false,
            'is_similarity_checked' => true,
            'is_cluster_main' => true,
        ]);
    }
}
