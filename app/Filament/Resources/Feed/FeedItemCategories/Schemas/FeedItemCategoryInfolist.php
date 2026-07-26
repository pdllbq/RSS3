<?php

namespace App\Filament\Resources\Feed\FeedItemCategories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeedItemCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category')
                    ->schema([
                        TextEntry::make('term'),
                        TextEntry::make('label')
                            ->placeholder('-'),
                        TextEntry::make('scheme')
                            ->url(fn (?string $state): ?string => $state)
                            ->openUrlInNewTab()
                            ->placeholder('-'),
                        TextEntry::make('type')
                            ->placeholder('-'),
                        TextEntry::make('language')
                            ->placeholder('-'),
                        TextEntry::make('feed_sources_count')
                            ->label('Sources')
                            ->state(fn ($record): int => $record->feedSources()->count()),
                        TextEntry::make('feed_items_count')
                            ->label('Items')
                            ->state(fn ($record): int => $record->feedItems()->count()),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }
}
