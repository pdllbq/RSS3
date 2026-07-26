<?php

namespace App\Filament\Resources\Feed\FeedSources\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeedSourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Source')
                    ->schema([
                        TextInput::make('custom_title')
                            ->required(),
                        TextInput::make('source_title'),
                        TextInput::make('url')
                            ->label('Feed URL')
                            ->url()
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('site_url')
                            ->label('Site URL')
                            ->url(),
                        TextInput::make('language')
                            ->maxLength(8),
                        Select::make('categories')
                            ->relationship('categories', 'term')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        Textarea::make('source_description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Channel metadata')
                    ->schema([
                        TextInput::make('source_link')
                            ->url(),
                        TextInput::make('source_permalink')
                            ->url(),
                        DateTimePicker::make('source_date'),
                        TextInput::make('source_author'),
                        Textarea::make('source_authors')
                            ->formatStateUsing(fn (mixed $state): ?string => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        TextInput::make('source_image_url')
                            ->label('Image URL')
                            ->url(),
                        TextInput::make('source_favicon')
                            ->label('Favicon URL')
                            ->url(),
                        TextInput::make('source_item_quantity')
                            ->numeric(),
                    ])
                    ->columns(2)
                    ->collapsed(),
                Section::make('Ownership and sync')
                    ->schema([
                        Select::make('added_by')
                            ->options([
                                'system' => 'System',
                                'user' => 'User',
                                'admin' => 'Admin',
                            ])
                            ->required()
                            ->default('system'),
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->required(),
                        DateTimePicker::make('last_fetched_at'),
                        DateTimePicker::make('last_success_at'),
                        Textarea::make('last_error')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('source_raw_data')
                            ->label('Raw data')
                            ->formatStateUsing(fn (mixed $state): ?string => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                            ->rows(8)
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
