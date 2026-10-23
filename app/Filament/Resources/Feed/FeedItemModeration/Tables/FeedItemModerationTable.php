<?php

namespace App\Filament\Resources\Feed\FeedItemModeration\Tables;

use App\Domain\Feed\Actions\SetManualGlobalCategoryAction;
use App\Filament\Resources\Feed\FeedItems\Schemas\FeedItemForm;
use App\Models\Feed\FeedItem;
use App\Models\Feed\GlobalCategory;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class FeedItemModerationTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->square()
                    ->size(60)
                    ->placeholder('-'),

                TextColumn::make('title')
                    ->label('Article / Description')
                    ->weight('bold')
                    ->url(fn (FeedItem $record): ?string => $record->url)
                    ->openUrlInNewTab()
                    ->description(fn (FeedItem $record): ?string => Str::limit(
                        strip_tags((string) ($record->description ?: $record->content)),
                        180
                    ))
                    ->wrap()
                    ->searchable(['title', 'description']),

                TextColumn::make('feedSource.source_title')
                    ->label('Source')
                    ->formatStateUsing(fn (FeedItem $record): string => $record->feedSource?->custom_title
                        ?: $record->feedSource?->source_title
                        ?: '-'
                    )
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('language')
                    ->label('Lang')
                    ->badge()
                    ->alignCenter()
                    ->color(fn (?string $state): string => match ($state) {
                        'ru' => 'info',
                        'lv' => 'warning',
                        'en' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('globalCategory.name')
                    ->label('Suggested / Current')
                    ->formatStateUsing(function (FeedItem $record): string {
                        if (! $record->globalCategory) {
                            return 'None';
                        }

                        return $record->globalCategory->path;
                    })
                    ->badge()
                    ->color(fn (FeedItem $record): string => $record->global_category_id ? 'warning' : 'gray'),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('language')
                    ->options([
                        'ru' => 'Russian (ru)',
                        'lv' => 'Latvian (lv)',
                        'en' => 'English (en)',
                    ]),

                SelectFilter::make('feed_source_id')
                    ->label('Source')
                    ->relationship('feedSource', 'source_title')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('setCategory')
                    ->label('Set category')
                    ->icon(Heroicon::OutlinedTag)
                    ->button()
                    ->color('primary')
                    ->modalHeading(fn (FeedItem $record): string => 'Set category: '.Str::limit($record->title, 60))
                    ->modalDescription(fn (FeedItem $record): ?string => Str::limit(
                        strip_tags((string) ($record->description ?: $record->content)),
                        200
                    ))
                    ->schema([
                        Select::make('global_category_id')
                            ->label('Global category')
                            ->options(fn (FeedItem $record): array => FeedItemForm::globalCategoryOptions($record->language))
                            ->default(fn (FeedItem $record): ?int => $record->global_category_id)
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
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
                    ->label('No category')
                    ->icon(Heroicon::OutlinedXMark)
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Mark as having no category?')
                    ->modalDescription('This item will be saved without a global category and removed from the moderation queue.')
                    ->action(function (FeedItem $record): void {
                        app(SetManualGlobalCategoryAction::class)->skipGlobalCategory($record);
                    })
                    ->successNotificationTitle('Marked as not belonging to a global category'),

                Action::make('preview')
                    ->label('Preview')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('gray')
                    ->modalHeading(fn (FeedItem $record): string => $record->title)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->schema([
                        Placeholder::make('article_preview')
                            ->hiddenLabel()
                            ->content(fn (FeedItem $record): HtmlString => new HtmlString(
                                '<div class="space-y-4">'.
                                ($record->image_url ? '<div class="mb-3"><img src="'.e($record->image_url).'" alt="" class="max-h-60 rounded object-cover"></div>' : '').
                                '<div class="text-sm text-gray-500 mb-2">'.
                                '<strong>Source:</strong> '.e($record->feedSource?->custom_title ?: $record->feedSource?->source_title ?: '-').
                                ' | <strong>Language:</strong> '.e($record->language ?: '-').
                                ' | <strong>Published:</strong> '.e($record->published_at?->format('d.m.Y H:i') ?? '-').
                                '</div>'.
                                '<div class="prose max-w-none text-sm leading-relaxed">'.
                                nl2br(e(strip_tags((string) ($record->content ?: $record->description)))).
                                '</div>'.
                                '<div class="pt-3">'.
                                '<a href="'.e($record->url).'" target="_blank" rel="noopener noreferrer" class="text-primary-600 underline font-medium text-sm">Open original article &rarr;</a>'.
                                '</div>'.
                                '</div>'
                            )),
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('setCategory')
                        ->label('Set category for selected')
                        ->icon(Heroicon::OutlinedTag)
                        ->schema([
                            Select::make('global_category_id')
                                ->label('Global category')
                                ->options(function (): array {
                                    return GlobalCategory::query()
                                        ->with('parent.ancestors')
                                        ->whereDoesntHave('children')
                                        ->orderBy('language')
                                        ->orderBy('name')
                                        ->get()
                                        ->mapWithKeys(fn (GlobalCategory $category): array => [
                                            $category->getKey() => '['.strtoupper($category->language).'] '.$category->path,
                                        ])
                                        ->all();
                                })
                                ->required()
                                ->searchable()
                                ->preload(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $categoryId = (int) $data['global_category_id'];
                            $action = app(SetManualGlobalCategoryAction::class);
                            $successCount = 0;
                            $skippedCount = 0;

                            foreach ($records as $record) {
                                try {
                                    $action->execute($record, $categoryId);
                                    $successCount++;
                                } catch (\InvalidArgumentException) {
                                    $skippedCount++;
                                }
                            }

                            if ($skippedCount > 0) {
                                Notification::make()
                                    ->warning()
                                    ->title("Updated {$successCount} items. {$skippedCount} skipped due to language mismatch.")
                                    ->send();
                            } else {
                                Notification::make()
                                    ->success()
                                    ->title("Category assigned to {$successCount} items.")
                                    ->send();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('skipCategory')
                        ->label('Mark selected as no category')
                        ->icon(Heroicon::OutlinedXMark)
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $action = app(SetManualGlobalCategoryAction::class);
                            $records->each(fn (FeedItem $record) => $action->skipGlobalCategory($record));

                            Notification::make()
                                ->success()
                                ->title("Marked {$records->count()} items as having no category.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
