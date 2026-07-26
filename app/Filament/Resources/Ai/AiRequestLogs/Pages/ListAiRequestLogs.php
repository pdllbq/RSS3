<?php

namespace App\Filament\Resources\Ai\AiRequestLogs\Pages;

use App\Filament\Resources\Ai\AiRequestLogs\AiRequestLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAiRequestLogs extends ListRecords
{
    protected static string $resource = AiRequestLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
