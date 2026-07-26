<?php

namespace App\Filament\Resources\Feed\FeedItems\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FeedItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('feedSource.id')
                    ->label('Feed source'),
                TextEntry::make('guid')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('url'),
                ImageEntry::make('image_url')
                    ->placeholder('-'),
                TextEntry::make('author')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('language')
                    ->placeholder('-'),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('fetched_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('checksum')
                    ->placeholder('-'),
                TextEntry::make('raw_payload')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('globalCategory.name')
                    ->label('Global category')
                    ->placeholder('-'),
                IconEntry::make('is_read')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
