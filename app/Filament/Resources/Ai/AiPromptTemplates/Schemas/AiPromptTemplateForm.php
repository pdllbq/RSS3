<?php

namespace App\Filament\Resources\Ai\AiPromptTemplates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AiPromptTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('task'),
                Textarea::make('system_prompt')
                    ->columnSpanFull(),
                Textarea::make('user_prompt')
                    ->columnSpanFull(),
                TextInput::make('model_variables'),
                TextInput::make('config'),
                Textarea::make('version')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
