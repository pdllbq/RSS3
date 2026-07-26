<?php

namespace App\Filament\Resources\Ai\AiModels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AiModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ai_provider_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('provider_model_id')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('price_input')
                    ->numeric(),
                TextInput::make('price_output')
                    ->numeric(),
                TextInput::make('context_window')
                    ->numeric(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('config'),
            ]);
    }
}
