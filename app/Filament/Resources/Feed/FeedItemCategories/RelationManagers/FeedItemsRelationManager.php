<?php

namespace App\Filament\Resources\Feed\FeedItemCategories\RelationManagers;

use App\Filament\Resources\Feed\FeedItems\FeedItemResource;
use App\Models\Feed\FeedItem;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FeedItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'feedItems';

    protected static ?string $title = 'Items';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('feedSource.custom_title')
                    ->label('Source')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(70),
                TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->limit(50)
                    ->url(fn (string $state): string => $state)
                    ->openUrlInNewTab(),
                TextColumn::make('author')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('language')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_read')
                    ->label('Read')
                    ->boolean(),
                TextColumn::make('fetched_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('feed_source_id')
                    ->label('Source')
                    ->relationship('feedSource', 'custom_title')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_read')
                    ->label('Read'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (FeedItem $record): string => FeedItemResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->url(fn (FeedItem $record): string => FeedItemResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
