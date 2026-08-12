<?php

namespace App\Filament\Resources\Api\ApiAllowedIps\Pages;

use App\Filament\Resources\Api\ApiAllowedIps\ApiAllowedIpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApiAllowedIps extends ListRecords
{
    protected static string $resource = ApiAllowedIpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
