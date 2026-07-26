<?php

namespace App\Filament\Resources\Feed\FeedItems\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                    ->relationship('globalCategory', 'name'),
                Toggle::make('is_read')
                    ->required(),
            ]);
    }
}
