<?php

namespace App\Filament\Resources\Api\ApiAllowedIps\Pages;

use App\Filament\Resources\Api\ApiAllowedIps\ApiAllowedIpResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditApiAllowedIp extends EditRecord
{
    protected static string $resource = ApiAllowedIpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
