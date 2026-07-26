<?php

namespace App\Filament\Resources\Ai\AiProviders\Pages;

use App\Filament\Resources\Ai\AiProviders\AiProviderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAiProvider extends ViewRecord
{
    protected static string $resource = AiProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
