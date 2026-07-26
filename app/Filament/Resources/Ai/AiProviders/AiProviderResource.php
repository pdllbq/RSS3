<?php
namespace App\Filament\Resources\Ai\AiProviders;

use App\Filament\Resources\Ai\AiProviders\Pages\CreateAiProvider;
use App\Filament\Resources\Ai\AiProviders\Pages\EditAiProvider;
use App\Filament\Resources\Ai\AiProviders\Pages\ListAiProviders;
use App\Filament\Resources\Ai\AiProviders\Pages\ViewAiProvider;
use App\Filament\Resources\Ai\AiProviders\Schemas\AiProviderForm;
use App\Filament\Resources\Ai\AiProviders\Schemas\AiProviderInfolist;
use App\Filament\Resources\Ai\AiProviders\Tables\AiProvidersTable;
use App\Models\Ai\AiProvider;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AiProviderResource extends Resource
{
    protected static ?string $model = AiProvider::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'AI';

    public static function form(Schema $schema): Schema
    {
        return AiProviderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AiProviderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AiProvidersTable::configure($table);
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
            'index' => ListAiProviders::route('/'),
            'create' => CreateAiProvider::route('/create'),
            'view' => ViewAiProvider::route('/{record}'),
            'edit' => EditAiProvider::route('/{record}/edit'),
        ];
    }
}
