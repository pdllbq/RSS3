<?php

namespace App\Domain\Feed\Actions;

use App\Domain\Content\Services\ContentSanitizer;
use App\Domain\Feed\Mappers\FeedItemsDataMapper;
use App\Models\Feed\CategoryRule;
use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedItemCategory;

class SyncFeedItemsAction
{
    public function __construct(
        private FeedItemsDataMapper $feedItemsDataMapper,
        private ContentSanitizer $contentSanitizer
    ) {}

    public function execute($feedSource, array $feedItems)
    {
        $items = $this->feedItemsDataMapper->fromSimplePieItem($feedItems);

        $this->updateFeedItems($feedSource, $feedItems, $items);

        return $items;
    }

    protected function updateFeedItems($feedSource, $feedItems, array $items)
    {
        $existingItems = FeedItem::where('feed_source_id', $feedSource->id)->pluck('guid')->flip();

        foreach ($feedItems as $index => $item) {
            $guid = $item->get_id();

            if ($existingItems->has($guid)) {
                break;
            }

            $feedItem = FeedItem::create([
                'feed_source_id' => $feedSource->id,
                'title' => $this->contentSanitizer->plainText($item->get_title()),
                'url' => $item->get_link(),
                'image_url' => $items[$index]->image_url ?? null,
                'description' => $this->contentSanitizer->plainText($item->get_description()),
                'content' => $this->contentSanitizer->saveHtml($item->get_content()),
                'published_at' => $item->get_date('Y-m-d H:i:s') ?: null,
                'fetched_at' => now(),
                'author' => $item->get_author() ? $item->get_author()->get_name() : null,
                'guid' => $guid,
                'language' => $feedSource->language,
            ]);

            $categoryIds = $this->syncCategories($feedSource, $feedItem, $item->get_categories() ?: []);
            $globalCategoryId = $this->resolveGlobalCategoryId($feedSource, $categoryIds);

            if ($globalCategoryId !== null) {
                $feedItem->update(['global_category_id' => $globalCategoryId]);
            }
        }
    }

    protected function syncCategories($feedSource, FeedItem $feedItem, array $categories): array
    {
        $categoryIds = [];

        foreach ($categories as $category) {
            $term = $this->contentSanitizer->plainText(
                $category->get_term() ?: $category->get_label() ?: ''
            );

            if ($term === '') {
                continue;
            }

            $feedItemCategory = FeedItemCategory::firstOrCreate(
                ['term' => $term],
                [
                    'scheme' => $this->contentSanitizer->plainText($category->get_scheme()),
                    'label' => $this->contentSanitizer->plainText($category->get_label()),
                    'type' => $this->contentSanitizer->plainText($category->get_type()),
                    'language' => $feedSource->language,
                ]
            );

            $feedItem->categories()->syncWithoutDetaching($feedItemCategory->id);
            $feedSource->categories()->syncWithoutDetaching($feedItemCategory->id);

            $categoryIds[] = $feedItemCategory->id;
        }

        return array_values(array_unique($categoryIds));
    }

    protected function resolveGlobalCategoryId($feedSource, array $categoryIds): ?int
    {
        if ($categoryIds === []) {
            return null;
        }

        $rules = CategoryRule::query()
            ->whereIn('feed_item_category_id', $categoryIds)
            ->where(function ($query) use ($feedSource): void {
                $query
                    ->whereNull('language')
                    ->orWhere('language', $feedSource->language);
            })
            ->orderBy('id')
            ->get(['global_category_id', 'type']);

        $excludedGlobalCategoryIds = $rules
            ->where('type', CategoryRule::TYPE_EXCLUDE)
            ->pluck('global_category_id')
            ->all();

        return $rules
            ->where('type', CategoryRule::TYPE_INCLUDE)
            ->whereNotIn('global_category_id', $excludedGlobalCategoryIds)
            ->pluck('global_category_id')
            ->first();
    }
}
