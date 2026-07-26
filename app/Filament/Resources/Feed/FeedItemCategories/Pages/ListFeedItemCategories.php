<?php

namespace App\Filament\Resources\Feed\FeedItemCategories\Pages;

use App\Filament\Resources\Feed\FeedItemCategories\FeedItemCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedItemCategories extends ListRecords
{
    protected static string $resource = FeedItemCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
