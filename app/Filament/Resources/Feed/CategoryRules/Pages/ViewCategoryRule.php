<?php

namespace App\Filament\Resources\Feed\CategoryRules\Pages;

use App\Filament\Resources\Feed\CategoryRules\CategoryRuleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCategoryRule extends ViewRecord
{
    protected static string $resource = CategoryRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
