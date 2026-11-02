<?php

namespace App\Filament\Resources\Feed\FeedItemModeration\Pages;

use App\Filament\Resources\Feed\FeedItemModeration\FeedItemModerationResource;
use App\Models\Feed\FeedItem;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListFeedItemModeration extends ListRecords
{
    protected static string $resource = FeedItemModerationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'needs_moderation' => Tab::make('Needs moderation')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('needs_category_check', true))
                ->badge(fn () => FeedItem::where('needs_category_check', true)->count() ?: null)
                ->badgeColor('warning'),

            'all_recent' => Tab::make('All recent articles')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_manually_categorized', false))
                ->badge(fn () => FeedItem::where('is_manually_categorized', false)->count() ?: null),

            'manually_moderated' => Tab::make('Manually moderated')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_manually_categorized', true))
                ->badge(fn () => FeedItem::where('is_manually_categorized', true)->count() ?: null)
                ->badgeColor('success'),
        ];
    }
}
