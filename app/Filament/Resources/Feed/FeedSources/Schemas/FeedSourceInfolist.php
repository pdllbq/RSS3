<?php

namespace App\Filament\Resources\Feed\FeedSources\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeedSourceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Source')
                    ->schema([
                        TextEntry::make('custom_title'),
                        TextEntry::make('source_title')
                            ->placeholder('-'),
                        TextEntry::make('url')
                            ->label('Feed URL')
                            ->url(fn (string $state): string => $state)
                            ->openUrlInNewTab()
                            ->columnSpanFull(),
                        TextEntry::make('site_url')
                            ->label('Site URL')
                            ->url(fn (?string $state): ?string => $state)
                            ->openUrlInNewTab()
                            ->placeholder('-'),
                        TextEntry::make('language')
                            ->placeholder('-'),
                        TextEntry::make('categories.term')
                            ->badge()
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('feed_items_count')
                            ->label('Items')
                            ->state(fn ($record): int => $record->feedItems()->count()),
                        TextEntry::make('source_description')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Channel metadata')
                    ->schema([
                        TextEntry::make('source_link')
                            ->url(fn (?string $state): ?string => $state)
                            ->openUrlInNewTab()
                            ->placeholder('-'),
                        TextEntry::make('source_permalink')
                            ->url(fn (?string $state): ?string => $state)
                            ->openUrlInNewTab()
                            ->placeholder('-'),
                        TextEntry::make('source_date')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('source_author')
                            ->placeholder('-'),
                        TextEntry::make('source_authors')
                            ->formatStateUsing(fn (mixed $state): ?string => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                            ->placeholder('-')
                            ->columnSpanFull(),
                        ImageEntry::make('source_image_url')
                            ->label('Image')
                            ->placeholder('-'),
                        TextEntry::make('source_favicon')
                            ->label('Favicon URL')
                            ->url(fn (?string $state): ?string => $state)
                            ->openUrlInNewTab()
                            ->placeholder('-'),
                        TextEntry::make('source_item_quantity')
                            ->numeric()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsed(),
                Section::make('Ownership and sync')
                    ->schema([
                        TextEntry::make('added_by')
                            ->badge(),
                        TextEntry::make('user.name')
                            ->label('User')
                            ->placeholder('-'),
                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                        TextEntry::make('last_fetched_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('last_success_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('last_error')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('source_raw_data')
                            ->label('Raw data')
                            ->formatStateUsing(fn (mixed $state): ?string => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
