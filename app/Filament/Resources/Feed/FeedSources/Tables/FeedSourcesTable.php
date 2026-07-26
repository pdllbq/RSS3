<?php

namespace App\Filament\Resources\Feed\FeedSources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FeedSourcesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('custom_title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('source_title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('url')
                    ->label('Feed URL')
                    ->searchable()
                    ->limit(50)
                    ->url(fn (string $state): string => $state)
                    ->openUrlInNewTab(),
                TextColumn::make('site_url')
                    ->label('Site URL')
                    ->searchable()
                    ->limit(40)
                    ->url(fn (?string $state): ?string => $state)
                    ->openUrlInNewTab(),
                TextColumn::make('categories.term')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('language')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('source_link')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('source_permalink')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('source_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('source_author')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('source_image_url')
                    ->label('Image')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('source_favicon')
                    ->label('Favicon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('source_item_quantity')
                    ->label('Source items')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('feed_items_count')
                    ->label('Saved items')
                    ->counts('feedItems')
                    ->sortable(),
                TextColumn::make('added_by')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('last_fetched_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_success_at')
                    ->dateTime()
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
            ->filters([
                SelectFilter::make('categories')
                    ->relationship('categories', 'term')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('added_by')
                    ->options([
                        'system' => 'System',
                        'user' => 'User',
                        'admin' => 'Admin',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Active'),
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
