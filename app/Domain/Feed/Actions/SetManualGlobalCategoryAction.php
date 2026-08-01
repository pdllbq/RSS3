<?php

namespace App\Domain\Feed\Actions;

use App\Models\Feed\FeedItem;
use App\Models\Feed\GlobalCategory;
use InvalidArgumentException;

class SetManualGlobalCategoryAction
{
    public function __construct(
        protected GlobalCategory $globalCategory,
    ) {}

    public function execute(FeedItem $item, int $categoryId): void
    {
        $category = $this->globalCategory
            ->newQuery()
            ->where('id', $categoryId)
            ->where('language', $item->language)
            ->whereDoesntHave('children')
            ->first();

        if (! $category) {
            throw new InvalidArgumentException('Select a leaf category for the item language.');
        }

        $item->forceFill([
            'global_category_id' => $category->getKey(),
            'is_category_checked' => true,
            'needs_category_check' => false,
        ])->save();
    }

    public function skipGlobalCategory(FeedItem $item): void
    {
        $item->forceFill([
            'global_category_id' => null,
            'is_category_checked' => true,
            'needs_category_check' => false,
        ])->save();
    }
}
