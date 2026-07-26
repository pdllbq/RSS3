<?php

namespace App\Filament\Resources\Feed\GlobalCategories\Pages;

use App\Filament\Resources\Feed\GlobalCategories\GlobalCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGlobalCategories extends ListRecords
{
    protected static string $resource = GlobalCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
