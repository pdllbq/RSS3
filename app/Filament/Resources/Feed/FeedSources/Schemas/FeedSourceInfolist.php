<?php

namespace App\Filament\Resources\Feed\FeedSources\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FeedSourceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->placeholder('-'),
                TextEntry::make('custom_title'),
                TextEntry::make('url'),
                TextEntry::make('site_url')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('language')
                    ->placeholder('-'),
                TextEntry::make('added_by'),
                TextEntry::make('user.name')
                    ->label('User')
                    ->placeholder('-'),
                IconEntry::make('is_active')
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
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
