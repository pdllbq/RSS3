<?php

namespace App\Filament\Resources\Api\ApiAllowedIps\Pages;

use App\Filament\Resources\Api\ApiAllowedIps\ApiAllowedIpResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApiAllowedIp extends CreateRecord
{
    protected static string $resource = ApiAllowedIpResource::class;
}
