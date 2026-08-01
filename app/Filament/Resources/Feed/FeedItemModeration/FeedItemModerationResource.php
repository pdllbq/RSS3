<?php

namespace App\Filament\Resources\Feed\FeedItemModeration;

use App\Filament\Resources\Feed\FeedItemModeration\Pages\ListFeedItemModeration;
use App\Filament\Resources\Feed\FeedItems\Schemas\FeedItemForm;
use App\Filament\Resources\Feed\FeedItems\Schemas\FeedItemInfolist;
use App\Filament\Resources\Feed\FeedItems\Tables\FeedItemsTable;
use App\Models\Feed\FeedItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class FeedItemModerationResource extends Resource
{
    protected static ?string $model = FeedItem::class;

    protected static string|UnitEnum|null $navigationGroup = 'Feeds';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Category moderation';

    protected static ?string $modelLabel = 'item for moderation';

    protected static ?string $pluralModelLabel = 'items for moderation';

    protected static ?string $slug = 'feed-item-moderation';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('needs_category_check', true);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedItemModeration::route('/'),
        ];
    }
}
