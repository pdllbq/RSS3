<?php

namespace App\Filament\Widgets;

use App\Models\Feed\FeedItemCategory;
use Filament\Widgets\Widget;

class TopCategoriesWidget extends Widget
{
    protected string $view = 'filament.widgets.top-categories-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = -1;

    protected function getViewData(): array
    {
        return [
            'topCategories' => FeedItemCategory::query()
                ->withCount('feedItems')
                ->orderByDesc('feed_items_count')
                ->limit(8)
                ->get(),
        ];
    }
}
