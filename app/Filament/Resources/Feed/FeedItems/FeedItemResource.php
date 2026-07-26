<?php

namespace App\Filament\Resources\Feed\FeedItems;

use App\Filament\Resources\Feed\FeedItems\Pages\CreateFeedItem;
use App\Filament\Resources\Feed\FeedItems\Pages\EditFeedItem;
use App\Filament\Resources\Feed\FeedItems\Pages\ListFeedItems;
use App\Filament\Resources\Feed\FeedItems\Pages\ViewFeedItem;
use App\Filament\Resources\Feed\FeedItems\Schemas\FeedItemForm;
use App\Filament\Resources\Feed\FeedItems\Schemas\FeedItemInfolist;
use App\Filament\Resources\Feed\FeedItems\Tables\FeedItemsTable;
use App\Models\Feed\FeedItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FeedItemResource extends Resource
{
    protected static ?string $model = FeedItem::class;

    protected static UnitEnum|string|null $navigationGroup = 'Feeds';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FeedItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeedItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedItemsTable::configure($table);
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
            'index' => ListFeedItems::route('/'),
            'create' => CreateFeedItem::route('/create'),
            'view' => ViewFeedItem::route('/{record}'),
            'edit' => EditFeedItem::route('/{record}/edit'),
        ];
    }
}
