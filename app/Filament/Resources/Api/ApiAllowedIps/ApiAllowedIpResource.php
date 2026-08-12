<?php

namespace App\Filament\Resources\Api\ApiAllowedIps;

use App\Filament\Resources\Api\ApiAllowedIps\Pages\CreateApiAllowedIp;
use App\Filament\Resources\Api\ApiAllowedIps\Pages\EditApiAllowedIp;
use App\Filament\Resources\Api\ApiAllowedIps\Pages\ListApiAllowedIps;
use App\Filament\Resources\Api\ApiAllowedIps\Pages\ViewApiAllowedIp;
use App\Filament\Resources\Api\ApiAllowedIps\Schemas\ApiAllowedIpForm;
use App\Filament\Resources\Api\ApiAllowedIps\Schemas\ApiAllowedIpInfolist;
use App\Filament\Resources\Api\ApiAllowedIps\Tables\ApiAllowedIpsTable;
use App\Models\Api\ApiAllowedIp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApiAllowedIpResource extends Resource
{
    protected static ?string $model = ApiAllowedIp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ApiAllowedIpForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApiAllowedIpInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApiAllowedIpsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApiAllowedIps::route('/'),
            'create' => CreateApiAllowedIp::route('/create'),
            'view' => ViewApiAllowedIp::route('/{record}'),
            'edit' => EditApiAllowedIp::route('/{record}/edit'),
        ];
    }
}
