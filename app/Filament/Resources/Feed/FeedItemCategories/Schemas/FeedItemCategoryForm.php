<?php

namespace App\Filament\Resources\Feed\FeedItemCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeedItemCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category')
                    ->schema([
                        TextInput::make('term')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        TextInput::make('label'),
                        TextInput::make('scheme')
                            ->url(),
                        TextInput::make('type'),
                        TextInput::make('language')
                            ->maxLength(8),
                    ])
                    ->columns(2),
            ]);
    }
}
