<?php

namespace App\Filament\Pages\Ai;

use App\Domain\Ai\Actions\EvaluateCategorizationAction;
use App\Models\Ai\AiModel;
use App\Models\Ai\AiPromptTemplate;
use App\Models\Feed\FeedItem;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class TestCategorizationPage extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'AI';

    protected static ?string $navigationLabel = 'AI Testing';

    protected static ?string $title = 'AI Categorization Testing';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'ai-testing';

    protected string $view = 'filament.pages.ai.test-categorization';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public ?array $testResults = null;

    public function mount(): void
    {
        $this->form->fill([
            'model_id' => AiModel::where('is_active', true)
                ->where('type', '!=', 'embedding')
                ->value('id'),
            'prompt_template_id' => AiPromptTemplate::where('is_active', true)
                ->where('type', 'categorization')
                ->value('id'),
            'limit' => 10,
            'language' => null,
            'only_manual' => false,
        ]);
    }

    public function getSubheading(): ?string
    {
        $labeled = FeedItem::whereNotNull('global_category_id')->count();
        $manual = FeedItem::whereNotNull('global_category_id')
            ->where('is_manually_categorized', true)
            ->count();

        return "Ground truth database contains {$labeled} labeled articles ({$manual} explicitly verified by human moderators).";
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Evaluation Parameters')
                    ->description('Select an AI model (test, instruct, chat), prompt template, and dataset sample.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                            'lg' => 4,
                        ])->schema([
                            Select::make('model_id')
                                ->label('AI Model')
                                ->helperText('Supports any type (test, instruct, chat)')
                                ->options(fn () => AiModel::where('is_active', true)
                                    ->where('type', '!=', 'embedding')
                                    ->with('provider')
                                    ->get()
                                    ->mapWithKeys(fn (AiModel $m) => [
                                        $m->id => "[{$m->type}] {$m->name} ({$m->provider?->name})",
                                    ])
                                )
                                ->required()
                                ->searchable()
                                ->native(false),

                            Select::make('prompt_template_id')
                                ->label('Prompt Template')
                                ->helperText('Categorization prompt templates')
                                ->options(fn () => AiPromptTemplate::where('is_active', true)
                                    ->where('type', 'categorization')
                                    ->get()
                                    ->mapWithKeys(fn (AiPromptTemplate $p) => [
                                        $p->id => "{$p->name} (v{$p->version})",
                                    ])
                                )
                                ->required()
                                ->searchable()
                                ->native(false),

                            Select::make('limit')
                                ->label('Sample Size')
                                ->options([
                                    5 => '5 articles',
                                    10 => '10 articles',
                                    20 => '20 articles',
                                    50 => '50 articles',
                                    100 => '100 articles',
                                    0 => 'All available articles',
                                ])
                                ->default(10)
                                ->required()
                                ->native(false),

                            Select::make('language')
                                ->label('Language')
                                ->options([
                                    'ru' => 'Russian (ru)',
                                    'lv' => 'Latvian (lv)',
                                    'en' => 'English (en)',
                                ])
                                ->placeholder('All languages')
                                ->native(false),
                        ]),

                        Toggle::make('only_manual')
                            ->label('Only test articles marked as manually categorized (is_manually_categorized = true)')
                            ->helperText('Filters ground truth dataset strictly to articles reviewed by humans.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function runAiTest(EvaluateCategorizationAction $evaluator): void
    {
        $formData = $this->form->getState();

        $model = AiModel::with('provider')->find($formData['model_id'] ?? null);
        $prompt = AiPromptTemplate::find($formData['prompt_template_id'] ?? null);

        if (! $model || ! $prompt) {
            Notification::make()
                ->danger()
                ->title('Selected model or prompt not found')
                ->send();

            return;
        }

        $this->testResults = $evaluator->execute(
            model: $model,
            promptTemplate: $prompt,
            limit: (int) ($formData['limit'] ?? 10),
            language: $formData['language'] ?: null,
            onlyManual: (bool) ($formData['only_manual'] ?? false),
        );

        Notification::make()
            ->success()
            ->title("Test completed: {$this->testResults['accuracy']}% accuracy ({$this->testResults['matched']}/{$this->testResults['total']} matched)")
            ->send();
    }

    public function clearTestResults(): void
    {
        $this->testResults = null;
    }
}
