<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Feed\FeedSources\FeedSourceResource;
use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedSource;
use Filament\Widgets\Widget;

class ReadingQueueWidget extends Widget
{
    protected string $view = 'filament.widgets.reading-queue-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = -3;

    protected function getViewData(): array
    {
        $itemsCount = FeedItem::query()->count();
        $readItemsCount = FeedItem::query()->where('is_read', true)->count();

        return [
            'feedSourceResource' => FeedSourceResource::class,
            'readPercent' => $itemsCount > 0 ? round(($readItemsCount / $itemsCount) * 100) : 0,
            'topSources' => FeedSource::query()
                ->withCount([
                    'feedItems',
                    'feedItems as unread_feed_items_count' => fn ($query) => $query->where('is_read', false),
                ])
                ->orderByDesc('unread_feed_items_count')
                ->orderByDesc('feed_items_count')
                ->limit(6)
                ->get(),
        ];
    }
}
