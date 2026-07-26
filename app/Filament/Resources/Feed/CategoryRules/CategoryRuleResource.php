<?php

namespace App\Filament\Resources\Feed\CategoryRules;

use App\Filament\Resources\Feed\CategoryRules\Pages\CreateCategoryRule;
use App\Filament\Resources\Feed\CategoryRules\Pages\EditCategoryRule;
use App\Filament\Resources\Feed\CategoryRules\Pages\ListCategoryRules;
use App\Filament\Resources\Feed\CategoryRules\Pages\ViewCategoryRule;
use App\Filament\Resources\Feed\CategoryRules\Schemas\CategoryRuleForm;
use App\Filament\Resources\Feed\CategoryRules\Schemas\CategoryRuleInfolist;
use App\Filament\Resources\Feed\CategoryRules\Tables\CategoryRulesTable;
use App\Models\Feed\CategoryRule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CategoryRuleResource extends Resource
{
    protected static ?string $model = CategoryRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Category Rules';

    protected static ?string $modelLabel = 'category rule';

    protected static ?string $pluralModelLabel = 'category rules';

    protected static string|UnitEnum|null $navigationGroup = 'Feeds';

    public static function form(Schema $schema): Schema
    {
        return CategoryRuleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoryRuleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryRulesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'feedItemCategory',
                'globalCategory.parent.ancestors',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategoryRules::route('/'),
            'create' => CreateCategoryRule::route('/create'),
            'view' => ViewCategoryRule::route('/{record}'),
            'edit' => EditCategoryRule::route('/{record}/edit'),
        ];
    }
}
