<?php

namespace App\Filament\Resources\Feed\FeedItemCategories\Pages;

use App\Filament\Resources\Feed\FeedItemCategories\FeedItemCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedItemCategory extends ViewRecord
{
    protected static string $resource = FeedItemCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
