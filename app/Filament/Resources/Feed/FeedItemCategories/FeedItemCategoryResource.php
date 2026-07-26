<?php

namespace App\Filament\Resources\Feed\FeedItemCategories;

use App\Filament\Resources\Feed\FeedItemCategories\Pages\CreateFeedItemCategory;
use App\Filament\Resources\Feed\FeedItemCategories\Pages\EditFeedItemCategory;
use App\Filament\Resources\Feed\FeedItemCategories\Pages\ListFeedItemCategories;
use App\Filament\Resources\Feed\FeedItemCategories\Pages\ViewFeedItemCategory;
use App\Filament\Resources\Feed\FeedItemCategories\RelationManagers\FeedItemsRelationManager;
use App\Filament\Resources\Feed\FeedItemCategories\Schemas\FeedItemCategoryForm;
use App\Filament\Resources\Feed\FeedItemCategories\Schemas\FeedItemCategoryInfolist;
use App\Filament\Resources\Feed\FeedItemCategories\Tables\FeedItemCategoriesTable;
use App\Models\Feed\FeedItemCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FeedItemCategoryResource extends Resource
{
    protected static ?string $model = FeedItemCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Feed Categories';

    protected static ?string $modelLabel = 'feed category';

    protected static ?string $pluralModelLabel = 'feed categories';

    protected static string|UnitEnum|null $navigationGroup = 'Feeds';

    public static function form(Schema $schema): Schema
    {
        return FeedItemCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeedItemCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedItemCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            FeedItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedItemCategories::route('/'),
            'create' => CreateFeedItemCategory::route('/create'),
            'view' => ViewFeedItemCategory::route('/{record}'),
            'edit' => EditFeedItemCategory::route('/{record}/edit'),
        ];
    }
}
