<?php

namespace App\Filament\Resources\Ai\AiRequestLogs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AiRequestLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ai_model_id')
                    ->required()
                    ->numeric(),
                TextInput::make('ai_provider_id')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('task'),
                Textarea::make('prompt')
                    ->columnSpanFull(),
                Textarea::make('response')
                    ->columnSpanFull(),
                TextInput::make('tokens_input')
                    ->numeric(),
                TextInput::make('tokens_output')
                    ->numeric(),
                TextInput::make('tokens_total')
                    ->numeric(),
                TextInput::make('cost')
                    ->numeric()
                    ->prefix('$'),
                Textarea::make('error_message')
                    ->columnSpanFull(),
                TextInput::make('request_payload'),
                TextInput::make('response_payload'),
                TextInput::make('messages'),
                TextInput::make('embedding_1024'),
            ]);
    }
}
