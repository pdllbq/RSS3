<?php

namespace App\Filament\Resources\Ai\AiPromptTemplates\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AiPromptTemplateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('type'),
                TextEntry::make('task')
                    ->placeholder('-'),
                TextEntry::make('system_prompt')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('user_prompt')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('version')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
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
