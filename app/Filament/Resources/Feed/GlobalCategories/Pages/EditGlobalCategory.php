<?php

namespace App\Filament\Resources\Feed\GlobalCategories\Pages;

use App\Filament\Resources\Feed\GlobalCategories\GlobalCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGlobalCategory extends EditRecord
{
    protected static string $resource = GlobalCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
