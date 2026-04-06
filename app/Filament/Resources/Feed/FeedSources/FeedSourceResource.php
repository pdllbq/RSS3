<?php

namespace App\Filament\Resources\Feed\FeedSources;

use App\Filament\Resources\Feed\FeedSources\Pages\CreateFeedSource;
use App\Filament\Resources\Feed\FeedSources\Pages\EditFeedSource;
use App\Filament\Resources\Feed\FeedSources\Pages\ListFeedSources;
use App\Filament\Resources\Feed\FeedSources\Pages\ViewFeedSource;
use App\Filament\Resources\Feed\FeedSources\Schemas\FeedSourceForm;
use App\Filament\Resources\Feed\FeedSources\Schemas\FeedSourceInfolist;
use App\Filament\Resources\Feed\FeedSources\Tables\FeedSourcesTable;
use App\Models\Feed\FeedSource;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeedSourceResource extends Resource
{
    protected static ?string $model = FeedSource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FeedSourceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeedSourceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedSourcesTable::configure($table);
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
            'index' => ListFeedSources::route('/'),
            'create' => CreateFeedSource::route('/create'),
            'view' => ViewFeedSource::route('/{record}'),
            'edit' => EditFeedSource::route('/{record}/edit'),
        ];
    }
}
