<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Feed\FeedSources\FeedSourceResource;
use App\Models\Feed\FeedSource;
use Filament\Widgets\Widget;

class SourceHealthWidget extends Widget
{
    protected string $view = 'filament.widgets.source-health-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = -2;

    protected function getViewData(): array
    {
        return [
            'feedSourceResource' => FeedSourceResource::class,
            'problemSources' => FeedSource::query()
                ->whereNotNull('last_error')
                ->latest('last_fetched_at')
                ->limit(4)
                ->get(),
        ];
    }
}
