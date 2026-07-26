<?php

namespace App\Filament\Resources\Feed\FeedItemCategories\Pages;

use App\Filament\Resources\Feed\FeedItemCategories\FeedItemCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedItemCategory extends EditRecord
{
    protected static string $resource = FeedItemCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
