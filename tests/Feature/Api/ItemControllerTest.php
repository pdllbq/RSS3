<?php

namespace Tests\Feature\Api;

use App\Http\Middleware\ApiAllowIpMiddleware;
use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedSource;
use App\Models\Feed\GlobalCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_paginated_items_for_category_and_language(): void
    {
        $this->withoutMiddleware(ApiAllowIpMiddleware::class);

        $source = FeedSource::create([
            'custom_title' => 'Source',
            'url' => 'https://example.com/feed.xml',
            'language' => 'ru',
        ]);

        $category = GlobalCategory::create([
            'name' => 'Technology',
            'slug' => 'technology',
            'language' => 'ru',
        ]);

        $childCategory = GlobalCategory::create([
            'parent_id' => $category->id,
            'name' => 'AI',
            'slug' => 'ai',
            'language' => 'ru',
        ]);

        $lvCategory = GlobalCategory::create([
            'name' => 'Technology LV',
            'slug' => 'technology-lv',
            'language' => 'lv',
        ]);

        $firstItem = $this->createDisplayableItem($source, $childCategory, 'ru', 'First item', now());
        $secondItem = $this->createDisplayableItem($source, $category, 'ru', 'Second item', now()->subMinute());
        $this->createDisplayableItem($source, $lvCategory, 'lv', 'Wrong language', now()->addMinute());
        $this->createDisplayableItem($source, $category, 'ru', 'Second page', now()->subMinutes(2));

        $response = $this->getJson("/api/items/category/{$category->id}?language=ru&per_page=2");

        $response
            ->assertOk()
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3)
            ->assertJsonPath('data.0.id', $firstItem->id)
            ->assertJsonPath('data.1.id', $secondItem->id)
            ->assertJsonCount(2, 'data');
    }

    public function test_it_does_not_return_category_for_another_language(): void
    {
        $this->withoutMiddleware(ApiAllowIpMiddleware::class);

        $category = GlobalCategory::create([
            'name' => 'Technology',
            'slug' => 'technology',
            'language' => 'lv',
        ]);

        $this->getJson("/api/items/category/{$category->id}?language=ru")
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
            'title' => $title,
            'url' => 'https://example.com/'.str($title)->slug(),
            'language' => $language,
            'published_at' => $publishedAt,
            'global_category_id' => $category->id,
            'is_category_checked' => true,
            'needs_category_check' => false,
            'is_similarity_checked' => true,
            'is_cluster_main' => true,
        ]);
    }
}
