<?php

namespace App\Filament\Resources\Ai\AiProvaiders\Pages;

use App\Filament\Resources\Ai\AiProvaiders\AiProvaiderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAiProvaiders extends ListRecords
{
    protected static string $resource = AiProvaiderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
