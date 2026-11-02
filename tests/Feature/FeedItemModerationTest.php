<?php

namespace Tests\Feature;

use App\Domain\Feed\Actions\SetManualGlobalCategoryAction;
use App\Filament\Resources\Feed\FeedItemModeration\FeedItemModerationResource;
use App\Filament\Resources\Feed\FeedItemModeration\Pages\ListFeedItemModeration;
use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedSource;
use App\Models\Feed\GlobalCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedItemModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_navigation_badge_shows_count_of_items_needing_check(): void
    {
        $feedSource = FeedSource::create([
            'custom_title' => 'Test Source',
            'url' => 'https://example.com/rss',
            'language' => 'ru',
            'is_active' => true,
        ]);

        FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-1',
            'title' => 'Test article 1',
            'url' => 'https://example.com/1',
            'language' => 'ru',
            'needs_category_check' => true,
            'is_category_checked' => true,
        ]);

        FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-2',
            'title' => 'Test article 2',
            'url' => 'https://example.com/2',
            'language' => 'ru',
            'needs_category_check' => false,
            'is_category_checked' => true,
        ]);

        $this->assertSame('1', FeedItemModerationResource::getNavigationBadge());
    }

    public function test_moderation_page_tabs_and_queries(): void
    {
        $feedSource = FeedSource::create([
            'custom_title' => 'Test Source',
            'url' => 'https://example.com/rss-tabs',
            'language' => 'ru',
            'is_active' => true,
        ]);

        $needingCheck = FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-need',
            'title' => 'Needs Check Article',
            'url' => 'https://example.com/need',
            'language' => 'ru',
            'needs_category_check' => true,
            'is_category_checked' => true,
        ]);

        $notNeedingCheck = FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-not-need',
            'title' => 'Regular Article',
            'url' => 'https://example.com/not-need',
            'language' => 'ru',
            'needs_category_check' => false,
            'is_category_checked' => false,
        ]);

        $manuallyCategorized = FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-manual',
            'title' => 'Manually Categorized Article',
            'url' => 'https://example.com/manual',
            'language' => 'ru',
            'needs_category_check' => false,
            'is_category_checked' => true,
            'is_manually_categorized' => true,
        ]);

        $page = new ListFeedItemModeration;
        $tabs = $page->getTabs();

        $this->assertArrayHasKey('needs_moderation', $tabs);
        $this->assertArrayHasKey('all_recent', $tabs);
        $this->assertArrayHasKey('manually_moderated', $tabs);

        // Check needs_moderation tab query
        $needsQuery = $tabs['needs_moderation']->modifyQuery(FeedItem::query());
        $needsIds = $needsQuery->pluck('id')->all();

        $this->assertContains($needingCheck->id, $needsIds);
        $this->assertNotContains($notNeedingCheck->id, $needsIds);
        $this->assertNotContains($manuallyCategorized->id, $needsIds);

        // Check all_recent tab query - excludes manually categorized items
        $allQuery = $tabs['all_recent']->modifyQuery(FeedItem::query());
        $allIds = $allQuery->pluck('id')->all();

        $this->assertContains($needingCheck->id, $allIds);
        $this->assertContains($notNeedingCheck->id, $allIds);
        $this->assertNotContains($manuallyCategorized->id, $allIds);

        // Check manually_moderated tab query - includes only manually categorized items
        $manualQuery = $tabs['manually_moderated']->modifyQuery(FeedItem::query());
        $manualIds = $manualQuery->pluck('id')->all();

        $this->assertContains($manuallyCategorized->id, $manualIds);
        $this->assertNotContains($needingCheck->id, $manualIds);
        $this->assertNotContains($notNeedingCheck->id, $manualIds);

        // Check badges
        $this->assertSame(1, $tabs['needs_moderation']->getBadge());
        $this->assertSame(2, $tabs['all_recent']->getBadge());
        $this->assertSame(1, $tabs['manually_moderated']->getBadge());
    }

    public function test_setting_category_removes_item_from_moderation_queue(): void
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

        $page = new ListFeedItemModeration;
        $needsTab = $page->getTabs()['needs_moderation'];
        $needsIds = $needsTab->modifyQuery(FeedItem::query())->pluck('id')->all();

        $this->assertNotContains($item->id, $needsIds);

        $manualIds = $page->getTabs()['manually_moderated']->modifyQuery(FeedItem::query())->pluck('id')->all();
        $this->assertContains($item->id, $manualIds);
    }

    public function test_setting_category_on_item_not_marked_for_moderation(): void
    {
        $feedSource = FeedSource::create([
            'custom_title' => 'Test Source',
            'url' => 'https://example.com/rss-recent',
            'language' => 'ru',
            'is_active' => true,
        ]);

        $category = GlobalCategory::forceCreate([
            'name' => 'Leaf Category 2',
            'slug' => 'leaf-category-test-2',
            'language' => 'ru',
        ]);

        // Item from the all_recent tab that wasn't flagged for moderation
        $item = FeedItem::forceCreate([
            'feed_source_id' => $feedSource->id,
            'guid' => 'test-guid-recent-1',
            'title' => 'Recent unchecked article',
            'url' => 'https://example.com/recent-1',
            'language' => 'ru',
            'needs_category_check' => false,
            'is_category_checked' => false,
        ]);

        $action = app(SetManualGlobalCategoryAction::class);
        $action->execute($item, $category->id);

        $item->refresh();

        $this->assertFalse($item->needs_category_check);
        $this->assertTrue($item->is_category_checked);
        $this->assertSame($category->id, $item->global_category_id);
        $this->assertTrue($item->is_manually_categorized);

        $page = new ListFeedItemModeration;
        $allRecentIds = $page->getTabs()['all_recent']->modifyQuery(FeedItem::query())->pluck('id')->all();
        $this->assertNotContains($item->id, $allRecentIds);

        $manualIds = $page->getTabs()['manually_moderated']->modifyQuery(FeedItem::query())->pluck('id')->all();
        $this->assertContains($item->id, $manualIds);
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
        $this->assertTrue($item->is_manually_categorized);

        $page = new ListFeedItemModeration;
        $needsTab = $page->getTabs()['needs_moderation'];
        $needsIds = $needsTab->modifyQuery(FeedItem::query())->pluck('id')->all();

        $this->assertNotContains($item->id, $needsIds);

        $manualIds = $page->getTabs()['manually_moderated']->modifyQuery(FeedItem::query())->pluck('id')->all();
        $this->assertContains($item->id, $manualIds);

        $allRecentIds = $page->getTabs()['all_recent']->modifyQuery(FeedItem::query())->pluck('id')->all();
        $this->assertNotContains($item->id, $allRecentIds);

        $manualIds = $page->getTabs()['manually_moderated']->modifyQuery(FeedItem::query())->pluck('id')->all();
        $this->assertContains($item->id, $manualIds);
    }
}
