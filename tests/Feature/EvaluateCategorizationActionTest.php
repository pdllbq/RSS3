<?php

namespace Tests\Feature;

use App\Domain\Ai\Actions\EvaluateCategorizationAction;
use App\Domain\Ai\Actions\GetChatAnswerAction;
use App\Domain\Ai\Infrastructures\OpenRouterChatClient;
use App\Domain\Ai\Infrastructures\OpenRouterEmbeddingClient;
use App\Domain\Ai\Managers\AiClientManager;
use App\Filament\Pages\Ai\TestCategorizationPage;
use App\Models\Ai\AiModel;
use App\Models\Ai\AiPromptTemplate;
use App\Models\Ai\AiProvider;
use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedSource;
use App\Models\Feed\GlobalCategory;
use App\Support\Json\JsonValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class EvaluateCategorizationActionTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_evaluation_calculates_accuracy_and_does_not_mutate_database(): void
    {
        $provider = AiProvider::create([
            'name' => 'OpenRouter',
            'slug' => 'openrouter-1',
            'base_url' => 'https://openrouter.ai/api/v1',
            'is_active' => true,
        ]);

        $model = AiModel::create([
            'ai_provider_id' => $provider->id,
            'name' => 'Test Model',
            'slug' => 'test-model',
            'provider_model_id' => 'test/model',
            'type' => 'test',
            'is_active' => true,
        ]);

        $promptTemplate = AiPromptTemplate::create([
            'name' => 'Test Prompt',
            'slug' => 'test-prompt',
            'type' => 'categorization',
            'task' => 'classification',
            'user_prompt' => "Categories:\n{{categories_json}}\nArticle:\n{{title}}\n{{content}}",
            'is_active' => true,
        ]);

        $categoryA = GlobalCategory::create([
            'name' => 'Category A',
            'slug' => 'category-a',
            'language' => 'ru',
        ]);

        $categoryB = GlobalCategory::create([
            'name' => 'Category B',
            'slug' => 'category-b',
            'language' => 'ru',
        ]);

        $source = FeedSource::create([
            'custom_title' => 'Source',
            'url' => 'https://example.com/rss',
            'language' => 'ru',
        ]);

        $item1 = FeedItem::forceCreate([
            'feed_source_id' => $source->id,
            'guid' => 'test-1',
            'title' => 'Title 1',
            'url' => 'https://example.com/1',
            'language' => 'ru',
            'global_category_id' => $categoryA->id,
            'is_category_checked' => true,
            'needs_category_check' => false,
            'is_manually_categorized' => true,
        ]);

        $item2 = FeedItem::forceCreate([
            'feed_source_id' => $source->id,
            'guid' => 'test-2',
            'title' => 'Title 2',
            'url' => 'https://example.com/2',
            'language' => 'ru',
            'global_category_id' => $categoryB->id,
            'is_category_checked' => true,
            'needs_category_check' => false,
            'is_manually_categorized' => true,
        ]);

        // Mock GetChatAnswerAction to return Category A for both items
        // Result: Item 1 matches (expected A, predicted A), Item 2 mismatches (expected B, predicted A) => 50% accuracy
        $chatAction = Mockery::mock(GetChatAnswerAction::class);
        $chatAction->shouldReceive('execute')
            ->twice()
            ->andReturn('{"category_id": '.$categoryA->id.'}');

        $evaluator = new EvaluateCategorizationAction($chatAction, new JsonValidator);
        $report = $evaluator->execute($model, $promptTemplate, 10);

        $this->assertSame(2, $report['total']);
        $this->assertSame(1, $report['matched']);
        $this->assertSame(1, $report['mismatched']);
        $this->assertSame(0, $report['invalid_json']);
        $this->assertSame(50.0, $report['accuracy']);

        // Verify ground truth in database is untouched!
        $item1->refresh();
        $item2->refresh();
        $this->assertSame($categoryA->id, $item1->global_category_id);
        $this->assertSame($categoryB->id, $item2->global_category_id);
    }

    public function test_ai_client_manager_supports_test_model_type(): void
    {
        $provider = AiProvider::create([
            'name' => 'OpenRouter',
            'slug' => 'openrouter-1',
            'base_url' => 'https://openrouter.ai/api/v1',
            'is_active' => true,
        ]);

        $model = AiModel::create([
            'ai_provider_id' => $provider->id,
            'name' => 'Test Model',
            'slug' => 'test-model-2',
            'provider_model_id' => 'test/model-2',
            'type' => 'test',
            'is_active' => true,
        ]);

        $openRouterEmbeddingClient = Mockery::mock(OpenRouterEmbeddingClient::class);
        $openRouterChatClient = Mockery::mock(OpenRouterChatClient::class);

        $logId = null;
        $openRouterChatClient->shouldReceive('generateChatResponse')
            ->once()
            ->with('Hello', $model, true, $logId)
            ->andReturn('Response');

        $manager = new AiClientManager($openRouterEmbeddingClient, $openRouterChatClient);
        $result = $manager->getClient('Hello', $model, true, $logId);

        $this->assertSame('Response', $result);
    }

    public function test_command_runs_successfully(): void
    {
        $provider = AiProvider::create([
            'name' => 'OpenRouter',
            'slug' => 'openrouter-1',
            'base_url' => 'https://openrouter.ai/api/v1',
            'is_active' => true,
        ]);

        $model = AiModel::create([
            'ai_provider_id' => $provider->id,
            'name' => 'Test Model',
            'slug' => 'test-model-3',
            'provider_model_id' => 'test/model-3',
            'type' => 'test',
            'is_active' => true,
        ]);

        $promptTemplate = AiPromptTemplate::create([
            'name' => 'Content Category Classifier',
            'slug' => 'content-category-classifier-test',
            'type' => 'categorization',
            'task' => 'classification',
            'user_prompt' => "Categories:\n{{categories_json}}\nArticle:\n{{title}}\n{{content}}",
            'is_active' => true,
        ]);

        $category = GlobalCategory::create([
            'name' => 'Tech',
            'slug' => 'tech',
            'language' => 'ru',
        ]);

        $source = FeedSource::create([
            'custom_title' => 'Source',
            'url' => 'https://example.com/rss',
            'language' => 'ru',
        ]);

        FeedItem::forceCreate([
            'feed_source_id' => $source->id,
            'guid' => 'test-cmd-1',
            'title' => 'Command Test',
            'url' => 'https://example.com/cmd-1',
            'language' => 'ru',
            'global_category_id' => $category->id,
            'is_category_checked' => true,
            'needs_category_check' => false,
            'is_manually_categorized' => true,
        ]);

        $mockChat = Mockery::mock(GetChatAnswerAction::class);
        $mockChat->shouldReceive('execute')
            ->once()
            ->andReturn('{"category_id": '.$category->id.'}');

        $this->app->instance(GetChatAnswerAction::class, $mockChat);

        $this->artisan('ai:test-categorization', [
            '--model-id' => $model->id,
            '--prompt-id' => $promptTemplate->id,
            '--limit' => 5,
        ])
            ->expectsOutputToContain('Testing Model:')
            ->expectsOutputToContain('Accuracy:     100%')
            ->assertSuccessful();
    }

    public function test_filament_ai_testing_page_runs_evaluation(): void
    {
        $provider = AiProvider::create([
            'name' => 'OpenRouter',
            'slug' => 'openrouter-page',
            'base_url' => 'https://openrouter.ai/api/v1',
            'is_active' => true,
        ]);

        $model = AiModel::create([
            'ai_provider_id' => $provider->id,
            'name' => 'Llama Test',
            'slug' => 'llama-test',
            'provider_model_id' => 'test/llama',
            'type' => 'test',
            'is_active' => true,
        ]);

        $promptTemplate = AiPromptTemplate::create([
            'name' => 'Classifier',
            'slug' => 'classifier-page',
            'type' => 'categorization',
            'task' => 'classification',
            'user_prompt' => "{{categories_json}}\n{{title}}\n{{content}}",
            'is_active' => true,
        ]);

        $category = GlobalCategory::create([
            'name' => 'World',
            'slug' => 'world',
            'language' => 'ru',
        ]);

        $source = FeedSource::create([
            'custom_title' => 'Source',
            'url' => 'https://example.com/rss',
            'language' => 'ru',
        ]);

        FeedItem::forceCreate([
            'feed_source_id' => $source->id,
            'guid' => 'test-page-1',
            'title' => 'Page Test Article',
            'url' => 'https://example.com/page-1',
            'language' => 'ru',
            'global_category_id' => $category->id,
            'is_category_checked' => true,
            'needs_category_check' => false,
            'is_manually_categorized' => true,
        ]);

        $mockChat = Mockery::mock(GetChatAnswerAction::class);
        $mockChat->shouldReceive('execute')
            ->once()
            ->andReturn('{"category_id": '.$category->id.'}');

        $evaluator = new EvaluateCategorizationAction($mockChat, new JsonValidator);

        $page = new TestCategorizationPage;
        $page->mount();

        $this->assertEquals($model->id, $page->data['model_id']);
        $this->assertEquals($promptTemplate->id, $page->data['prompt_template_id']);
        $this->assertGreaterThanOrEqual(1, FeedItem::whereNotNull('global_category_id')->count());

        $page->runAiTest($evaluator);

        $this->assertNotNull($page->testResults);
        $this->assertSame(100.0, $page->testResults['accuracy']);
        $this->assertSame(1, $page->testResults['matched']);
    }
}
