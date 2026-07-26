<?php

namespace App\Filament\Resources\Ai\AiRequestLogs\Pages;

use App\Filament\Resources\Ai\AiRequestLogs\AiRequestLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAiRequestLog extends ViewRecord
{
    protected static string $resource = AiRequestLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
