<?php

namespace App\Filament\Resources\Ai\AiRequestLogs\Pages;

use App\Filament\Resources\Ai\AiRequestLogs\AiRequestLogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAiRequestLog extends EditRecord
{
    protected static string $resource = AiRequestLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
