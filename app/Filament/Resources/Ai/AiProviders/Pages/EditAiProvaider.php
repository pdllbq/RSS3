<?php

namespace App\Filament\Resources\Ai\AiProvaiders\Pages;

use App\Filament\Resources\Ai\AiProvaiders\AiProvaiderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAiProvaider extends EditRecord
{
    protected static string $resource = AiProvaiderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
