<?php

namespace App\Filament\Resources\Feed\GlobalCategories\Pages;

use App\Filament\Resources\Feed\GlobalCategories\GlobalCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGlobalCategory extends ViewRecord
{
    protected static string $resource = GlobalCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
