<?php

namespace App\Filament\Resources\Feed\CategoryRules\Tables;

use App\Models\Feed\CategoryRule;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoryRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('globalCategory.name')
                    ->label('Global category')
                    ->formatStateUsing(fn (CategoryRule $record): ?string => $record->globalCategory?->path)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('feedItemCategory.term')
                    ->label('Feed category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        CategoryRule::TYPE_INCLUDE => 'success',
                        CategoryRule::TYPE_EXCLUDE => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('language')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
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
                SelectFilter::make('type')
                    ->options([
                        CategoryRule::TYPE_INCLUDE => 'Include',
                        CategoryRule::TYPE_EXCLUDE => 'Exclude',
                    ]),
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
