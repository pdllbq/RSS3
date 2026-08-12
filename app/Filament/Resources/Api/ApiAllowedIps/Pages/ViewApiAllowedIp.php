<?php

namespace App\Filament\Resources\Api\ApiAllowedIps\Pages;

use App\Filament\Resources\Api\ApiAllowedIps\ApiAllowedIpResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewApiAllowedIp extends ViewRecord
{
    protected static string $resource = ApiAllowedIpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
