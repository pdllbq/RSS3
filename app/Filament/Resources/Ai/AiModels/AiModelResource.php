<?php

namespace App\Filament\Resources\Ai\AiModels;

use App\Filament\Resources\Ai\AiModels\Pages\CreateAiModel;
use App\Filament\Resources\Ai\AiModels\Pages\EditAiModel;
use App\Filament\Resources\Ai\AiModels\Pages\ListAiModels;
use App\Filament\Resources\Ai\AiModels\Schemas\AiModelForm;
use App\Filament\Resources\Ai\AiModels\Tables\AiModelsTable;
use App\Models\Ai\AiModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AiModelResource extends Resource
{
    protected static ?string $model = AiModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AiModelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AiModelsTable::configure($table);
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
            'index' => ListAiModels::route('/'),
            'create' => CreateAiModel::route('/create'),
            'edit' => EditAiModel::route('/{record}/edit'),
        ];
    }
}
