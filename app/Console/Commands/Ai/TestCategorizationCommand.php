<?php

namespace App\Console\Commands\Ai;

use App\Domain\Ai\Actions\EvaluateCategorizationAction;
use App\Models\Ai\AiModel;
use App\Models\Ai\AiPromptTemplate;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ai:test-categorization {--model-id= : ID of the AI model to test} {--prompt-id= : ID of the categorization prompt} {--limit=10 : Number of records to test} {--lang= : Filter by language} {--only-manual : Only test items explicitly flagged as manual}')]
#[Description('Test and evaluate categorization AI model accuracy against manually labeled items')]
class TestCategorizationCommand extends Command
{
    public function __construct(
        protected EvaluateCategorizationAction $evaluateAction,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $modelId = $this->option('model-id');
        $promptId = $this->option('prompt-id');
        $limit = (int) $this->option('limit');
        $language = $this->option('lang');
        $onlyManual = (bool) $this->option('only-manual');

        $model = $modelId
            ? AiModel::with('provider')->find($modelId)
            : AiModel::where('is_active', true)->where('type', '!=', 'embedding')->with('provider')->first();

        if (! $model) {
            $this->error('AI model not found.');

            return self::FAILURE;
        }

        $prompt = $promptId
            ? AiPromptTemplate::find($promptId)
            : AiPromptTemplate::where('type', 'categorization')->where('is_active', true)->first();

        if (! $prompt) {
            $this->error('Categorization prompt template not found.');

            return self::FAILURE;
        }

        $this->info("Testing Model: [{$model->id}] {$model->name} (type: {$model->type})");
        $this->info("Prompt: [{$prompt->id}] {$prompt->name}");
        $this->info("Testing up to {$limit} records...");

        $report = $this->evaluateAction->execute($model, $prompt, $limit, $language, $onlyManual);

        if ($report['total'] === 0) {
            $this->warn('No labeled records found for evaluation.');

            return self::SUCCESS;
        }

        $rows = [];
        foreach ($report['results'] as $res) {
            $statusIcon = match ($res['status']) {
                'match' => '✓ MATCH',
                'mismatch' => '✗ MISMATCH',
                'invalid_json' => '⚠ INVALID JSON',
                default => '⚠ ERROR',
            };

            $rows[] = [
                $res['item_id'],
                mb_strimwidth($res['title'], 0, 40, '...'),
                $res['expected_category_name'],
                $res['predicted_category_name'],
                $statusIcon,
            ];
        }

        $this->table(
            ['ID', 'Title', 'Expected Category', 'Predicted Category', 'Status'],
            $rows
        );

        $this->newLine();
        $this->line("Total Tested: {$report['total']}");
        $this->line("Matches:      {$report['matched']}");
        $this->line("Mismatches:   {$report['mismatched']}");
        $this->line("Invalid JSON: {$report['invalid_json']}");
        $this->line("Errors:       {$report['errors']}");
        $this->info("Accuracy:     {$report['accuracy']}%");

        return self::SUCCESS;
    }
}
