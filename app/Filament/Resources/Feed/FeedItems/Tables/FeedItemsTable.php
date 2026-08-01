<?php

namespace App\Filament\Resources\Feed\FeedItems\Tables;

use App\Domain\Feed\Actions\SetManualGlobalCategoryAction;
use App\Filament\Resources\Feed\FeedItems\FeedItemResource;
use App\Filament\Resources\Feed\FeedItems\Schemas\FeedItemForm;
use App\Models\Feed\FeedItem;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FeedItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('feedSource.id')
                    ->searchable(),
                TextColumn::make('guid')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('url')
                    ->searchable(),
                ImageColumn::make('image_url'),
                TextColumn::make('author')
                    ->searchable(),
                TextColumn::make('language')
                    ->searchable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fetched_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('checksum')
                    ->searchable(),
                TextColumn::make('globalCategory.name')
                    ->searchable(),
                IconColumn::make('is_read')
                    ->boolean(),
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
                TernaryFilter::make('needs_category_check')
                    ->label('Needs category check'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (FeedItem $record): string => FeedItemResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->url(fn (FeedItem $record): string => FeedItemResource::getUrl('edit', ['record' => $record])),
                Action::make('setCategory')
                    ->label('Set category')
                    ->schema([
                        Select::make('global_category_id')
                            ->label('Global category')
                            ->options(fn (FeedItem $record): array => FeedItemForm::globalCategoryOptions($record->language))
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
                    ->visible(fn (FeedItem $record): bool => $record->needs_category_check)
                    ->action(function (FeedItem $record, array $data): void {
                        $categoryId = (int) $data['global_category_id'];
                        $action = app(SetManualGlobalCategoryAction::class);

                        if ($categoryId === 0) {
                            $action->skipGlobalCategory($record);

                            return;
                        }

                        $action->execute($record, $categoryId);
                    })
                    ->successNotificationTitle('Category set'),
                Action::make('skipCategory')
                    ->label('No global category')
                    ->requiresConfirmation()
                    ->visible(fn (FeedItem $record): bool => $record->needs_category_check)
                    ->action(function (FeedItem $record): void {
                        app(SetManualGlobalCategoryAction::class)->skipGlobalCategory($record);
                    })
                    ->successNotificationTitle('Marked as not belonging to a global category'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
