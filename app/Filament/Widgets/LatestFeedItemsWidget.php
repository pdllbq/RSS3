<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Feed\FeedItems\FeedItemResource;
use App\Models\Feed\FeedItem;
use Filament\Widgets\Widget;

class LatestFeedItemsWidget extends Widget
{
    protected string $view = 'filament.widgets.latest-feed-items-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = -4;

    protected function getViewData(): array
    {
        return [
            'feedItemResource' => FeedItemResource::class,
            'recentItems' => FeedItem::query()
                ->with('feedSource')
                ->latest('published_at')
                ->latest('created_at')
                ->limit(6)
                ->get(),
        ];
    }
}
