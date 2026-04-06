<?php

namespace App\Filament\Resources\Feed\FeedSources\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FeedSourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title'),
                TextInput::make('custom_title')
                    ->required(),
                TextInput::make('url')
                    ->url()
                    ->required(),
                TextInput::make('site_url')
                    ->url(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('language'),
                TextInput::make('added_by')
                    ->required()
                    ->default('system'),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('last_fetched_at'),
                DateTimePicker::make('last_success_at'),
                Textarea::make('last_error')
                    ->columnSpanFull(),
            ]);
    }
}
