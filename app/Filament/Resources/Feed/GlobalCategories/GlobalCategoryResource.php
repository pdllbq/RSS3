<?php

namespace App\Filament\Resources\Feed\GlobalCategories;

use App\Filament\Resources\Feed\GlobalCategories\Pages\CreateGlobalCategory;
use App\Filament\Resources\Feed\GlobalCategories\Pages\EditGlobalCategory;
use App\Filament\Resources\Feed\GlobalCategories\Pages\ListGlobalCategories;
use App\Filament\Resources\Feed\GlobalCategories\Pages\ViewGlobalCategory;
use App\Filament\Resources\Feed\GlobalCategories\Schemas\GlobalCategoryForm;
use App\Filament\Resources\Feed\GlobalCategories\Schemas\GlobalCategoryInfolist;
use App\Filament\Resources\Feed\GlobalCategories\Tables\GlobalCategoriesTable;
use App\Models\Feed\GlobalCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GlobalCategoryResource extends Resource
{
    protected static ?string $model = GlobalCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return GlobalCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GlobalCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GlobalCategoriesTable::configure($table);
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
            'index' => ListGlobalCategories::route('/'),
            'create' => CreateGlobalCategory::route('/create'),
            'view' => ViewGlobalCategory::route('/{record}'),
            'edit' => EditGlobalCategory::route('/{record}/edit'),
        ];
    }
}
