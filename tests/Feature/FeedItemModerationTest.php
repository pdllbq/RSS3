<?php

namespace Tests\Feature;

use App\Domain\Feed\Actions\SetManualGlobalCategoryAction;
use App\Filament\Resources\Feed\FeedItemModeration\FeedItemModerationResource;
use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedSource;
use App\Models\Feed\GlobalCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedItemModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderation_query_only_includes_items_needing_category_check(): void
    {
        $feedSource = FeedSource::create([
            'custom_title' => 'Test Source',
            'url' => 'https://example.com/rss',
            'language' => 'ru',
            'is_active' => true,
        ]);

        $needingCheck = FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-1',
            'title' => 'Test article 1',
            'url' => 'https://example.com/1',
            'language' => 'ru',
            'needs_category_check' => true,
            'is_category_checked' => true,
        ]);

        $notNeedingCheck = FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-2',
            'title' => 'Test article 2',
            'url' => 'https://example.com/2',
            'language' => 'ru',
            'needs_category_check' => false,
            'is_category_checked' => true,
        ]);

        $queryIds = FeedItemModerationResource::getEloquentQuery()->pluck('id')->all();

        $this->assertContains($needingCheck->id, $queryIds);
        $this->assertNotContains($notNeedingCheck->id, $queryIds);

        $needingCheck->delete();
        $notNeedingCheck->delete();
        $feedSource->delete();
    }

    public function test_setting_category_removes_item_from_moderation(): void
    {
        $feedSource = FeedSource::create([
            'custom_title' => 'Test Source',
            'url' => 'https://example.com/rss-2',
            'language' => 'ru',
            'is_active' => true,
        ]);

        $category = GlobalCategory::forceCreate([
            'name' => 'Leaf Category',
            'slug' => 'leaf-category-test',
            'language' => 'ru',
        ]);

        $item = FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-3',
            'title' => 'Test article 3',
            'url' => 'https://example.com/3',
            'language' => 'ru',
            'needs_category_check' => true,
            'is_category_checked' => true,
        ]);

        $action = app(SetManualGlobalCategoryAction::class);
        $action->execute($item, $category->id);

        $item->refresh();

        $this->assertFalse($item->needs_category_check);
        $this->assertTrue($item->is_category_checked);
        $this->assertSame($category->id, $item->global_category_id);

        $queryIds = FeedItemModerationResource::getEloquentQuery()->pluck('id')->all();
        $this->assertNotContains($item->id, $queryIds);

        $item->delete();
        $category->delete();
        $feedSource->delete();
    }

    public function test_skipping_category_removes_item_from_moderation(): void
    {
        $feedSource = FeedSource::create([
            'custom_title' => 'Test Source',
            'url' => 'https://example.com/rss-3',
            'language' => 'ru',
            'is_active' => true,
        ]);

        $item = FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-4',
            'title' => 'Test article 4',
            'url' => 'https://example.com/4',
            'language' => 'ru',
            'needs_category_check' => true,
            'is_category_checked' => true,
        ]);

        $action = app(SetManualGlobalCategoryAction::class);
        $action->skipGlobalCategory($item);

        $item->refresh();

        $this->assertFalse($item->needs_category_check);
        $this->assertTrue($item->is_category_checked);
        $this->assertNull($item->global_category_id);

        $queryIds = FeedItemModerationResource::getEloquentQuery()->pluck('id')->all();
        $this->assertNotContains($item->id, $queryIds);

        $item->delete();
        $feedSource->delete();
    }
}
