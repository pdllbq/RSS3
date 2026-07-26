<?php

namespace App\Filament\Resources\Ai\AiProviders\Pages;

use App\Filament\Resources\Ai\AiProviders\AiProviderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAiProviders extends ListRecords
{
    protected static string $resource = AiProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
