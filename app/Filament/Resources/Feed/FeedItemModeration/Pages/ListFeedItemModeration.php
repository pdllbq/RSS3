<?php

namespace App\Filament\Resources\Feed\FeedItemModeration\Pages;

use App\Filament\Resources\Feed\FeedItemModeration\FeedItemModerationResource;
use Filament\Resources\Pages\ListRecords;

class ListFeedItemModeration extends ListRecords
{
    protected static string $resource = FeedItemModerationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
