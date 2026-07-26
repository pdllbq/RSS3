<?php

namespace App\Filament\Resources\Ai\AiRequestLogs;

use App\Filament\Resources\Ai\AiRequestLogs\Pages\CreateAiRequestLog;
use App\Filament\Resources\Ai\AiRequestLogs\Pages\EditAiRequestLog;
use App\Filament\Resources\Ai\AiRequestLogs\Pages\ListAiRequestLogs;
use App\Filament\Resources\Ai\AiRequestLogs\Pages\ViewAiRequestLog;
use App\Filament\Resources\Ai\AiRequestLogs\Schemas\AiRequestLogForm;
use App\Filament\Resources\Ai\AiRequestLogs\Schemas\AiRequestLogInfolist;
use App\Filament\Resources\Ai\AiRequestLogs\Tables\AiRequestLogsTable;
use App\Models\Ai\AiRequestLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AiRequestLogResource extends Resource
{
    protected static ?string $model = AiRequestLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AiRequestLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AiRequestLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AiRequestLogsTable::configure($table);
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
            'index' => ListAiRequestLogs::route('/'),
            'create' => CreateAiRequestLog::route('/create'),
            'view' => ViewAiRequestLog::route('/{record}'),
            'edit' => EditAiRequestLog::route('/{record}/edit'),
        ];
    }
}
