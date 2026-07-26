<?php

namespace App\Filament\Resources\Feed\CategoryRules\Pages;

use App\Filament\Resources\Feed\CategoryRules\CategoryRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoryRule extends EditRecord
{
    protected static string $resource = CategoryRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
