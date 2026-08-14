<?php

namespace App\Domain\Feed\Actions;

use App\Models\Feed\FeedItem;
use App\Models\Feed\GlobalCategory;
use Illuminate\Database\Eloquent\Collection;

class GetRssFeedItemsAction
{
    public function execute(string $language, ?GlobalCategory $category = null, int $limit = 50): Collection
    {
        $query = FeedItem::query()
            ->where('language', $language)
            ->displayable()
            ->with([
                'feedSource',
                'globalCategory',
            ])
            ->orderByDesc('published_at')
            ->limit($limit);

        if ($category) {
            $query->whereIn('global_category_id', [
                $category->getKey(),
                ...$category->descendantIds(),
            ]);
        }

        return $query->get();
    }
}
