<?php

namespace App\Filament\Widgets;

use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedSource;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class FeedStatsWidget extends Widget
{
    protected string $view = 'filament.widgets.feed-stats-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -5;

    protected function getViewData(): array
    {
        $itemsCount = FeedItem::query()->count();
        $readItemsCount = FeedItem::query()->where('is_read', true)->count();

        $staleSourcesCount = FeedSource::query()
            ->where('is_active', true)
            ->where(function ($query): void {
                $query
                    ->whereNull('last_success_at')
                    ->orWhere('last_success_at', '<', now()->subDay());
            })
            ->count();

        return [
            'activeSourcesCount' => FeedSource::query()->where('is_active', true)->count(),
            'itemsCount' => $itemsCount,
            'unreadItemsCount' => FeedItem::query()->where('is_read', false)->count(),
            'itemsToday' => FeedItem::query()->where('created_at', '>=', Carbon::today())->count(),
            'readPercent' => $itemsCount > 0 ? round(($readItemsCount / $itemsCount) * 100) : 0,
            'staleSourcesCount' => $staleSourcesCount,
        ];
    }
}
