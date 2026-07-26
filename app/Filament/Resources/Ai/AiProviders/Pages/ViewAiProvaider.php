<?php

namespace App\Filament\Resources\Ai\AiProvaiders\Pages;

use App\Filament\Resources\Ai\AiProvaiders\AiProvaiderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAiProvaider extends ViewRecord
{
    protected static string $resource = AiProvaiderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
