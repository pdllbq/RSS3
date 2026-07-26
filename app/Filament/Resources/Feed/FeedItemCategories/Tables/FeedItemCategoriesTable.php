<?php

namespace App\Filament\Resources\Feed\FeedItemCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeedItemCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('term')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('label')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('type')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('language')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('feed_sources_count')
                    ->label('Sources')
                    ->counts('feedSources')
                    ->sortable(),
                TextColumn::make('feed_items_count')
                    ->label('Items')
                    ->counts('feedItems')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
