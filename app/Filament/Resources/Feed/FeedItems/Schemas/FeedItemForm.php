<?php

namespace App\Filament\Resources\Feed\FeedItems\Schemas;

use App\Models\Feed\FeedItem;
use App\Models\Feed\GlobalCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FeedItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('feed_source_id')
                    ->relationship('feedSource', 'id')
                    ->required(),
                TextInput::make('guid'),
                TextInput::make('title')
                    ->required(),
                TextInput::make('url')
                    ->url()
                    ->required(),
                FileUpload::make('image_url')
                    ->image(),
                TextInput::make('author'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->columnSpanFull(),
                TextInput::make('language'),
                DateTimePicker::make('published_at'),
                DateTimePicker::make('fetched_at'),
                TextInput::make('checksum'),
                Textarea::make('raw_payload')
                    ->columnSpanFull(),
                Select::make('global_category_id')
                    ->label('Global category')
                    ->options(fn (?FeedItem $record): array => self::globalCategoryOptions($record?->language))
                    ->visible(fn (?FeedItem $record): bool => $record?->needs_category_check === true)
                    ->required()
                    ->searchable()
                    ->preload()
                    ->dehydrateStateUsing(fn (mixed $state): ?int => (int) $state === 0 ? null : (int) $state),
                Toggle::make('is_read')
                    ->required(),
            ]);
    }

    public static function globalCategoryOptions(?string $language): array
    {
        if (blank($language)) {
            return [];
        }

        $options = GlobalCategory::query()
            ->with('parent.ancestors')
            ->where('language', $language)
            ->whereDoesntHave('children')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (GlobalCategory $category): array => [
                $category->getKey() => $category->path,
            ])
            ->all();

        return [0 => 'No global category'] + $options;
    }
}
