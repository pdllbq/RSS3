<?php

namespace App\Filament\Resources\Ai\AiProvaiders;

use App\Filament\Resources\Ai\AiProvaiders\Pages\CreateAiProvaider;
use App\Filament\Resources\Ai\AiProvaiders\Pages\EditAiProvaider;
use App\Filament\Resources\Ai\AiProvaiders\Pages\ListAiProvaiders;
use App\Filament\Resources\Ai\AiProvaiders\Pages\ViewAiProvaider;
use App\Filament\Resources\Ai\AiProvaiders\Schemas\AiProvaiderForm;
use App\Filament\Resources\Ai\AiProvaiders\Schemas\AiProvaiderInfolist;
use App\Filament\Resources\Ai\AiProvaiders\Tables\AiProvaidersTable;
use App\Models\Ai\AiProvaider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AiProvaiderResource extends Resource
{
    protected static ?string $model = AiProvaider::class;

    protected static UnitEnum|string|null $navigationGroup = 'AI';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AiProvaiderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AiProvaiderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AiProvaidersTable::configure($table);
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
            'index' => ListAiProvaiders::route('/'),
            'create' => CreateAiProvaider::route('/create'),
            'view' => ViewAiProvaider::route('/{record}'),
            'edit' => EditAiProvaider::route('/{record}/edit'),
        ];
    }
}
