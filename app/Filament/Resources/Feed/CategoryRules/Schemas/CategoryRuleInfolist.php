<?php

namespace App\Filament\Resources\Feed\CategoryRules\Schemas;

use App\Models\Feed\CategoryRule;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryRuleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rule')
                    ->schema([
                        TextEntry::make('globalCategory.name')
                            ->label('Global category')
                            ->formatStateUsing(fn (CategoryRule $record): ?string => $record->globalCategory?->path)
                            ->placeholder('-'),
                        TextEntry::make('feedItemCategory.term')
                            ->label('Feed category')
                            ->placeholder('-'),
                        TextEntry::make('type')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => ucfirst($state))
                            ->color(fn (string $state): string => match ($state) {
                                CategoryRule::TYPE_INCLUDE => 'success',
                                CategoryRule::TYPE_EXCLUDE => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('language')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }
}
