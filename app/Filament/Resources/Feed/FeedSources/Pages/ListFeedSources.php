<?php

namespace App\Filament\Resources\Feed\FeedSources\Pages;

use App\Filament\Resources\Feed\FeedSources\FeedSourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedSources extends ListRecords
{
    protected static string $resource = FeedSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
