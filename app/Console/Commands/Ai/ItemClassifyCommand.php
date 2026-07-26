<?php

namespace App\Console\Commands\Ai;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Models\Feed\GlobalCategory;
use App\Models\Ai\AiPromptTemplate;
use App\Models\Feed\FeedItem;
use App\Domain\Ai\Actions\GetChatAnswerAction;
use App\Models\Ai\AiModel;
use App\Support\Json\JsonValidator;
use App\Domain\Ai\Actions\ItemSetGlobalCategoryAction;

#[Signature('item:classify')]
#[Description('Command description')]
class ItemClassifyCommand extends Command
{
    function __construct(
        protected GlobalCategory $globalCategory,
        protected AiPromptTemplate $aiPromptTemplate,
        protected FeedItem $feedItem,
        protected GetChatAnswerAction $getChatAnswerAction,
        protected JsonValidator $jsonValidator,
        protected ItemSetGlobalCategoryAction $itemSetGlobalCategoryAction,
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $item = $this->getItem();

        if(!$item) {
            return self::FAILURE;
        }

        $categories = $this->getCategories($item->language);

        if(!$categories) {
            return self::FAILURE;
        }

        $prompt = $this->getPrompt();

        if(!$prompt) {
            return self::FAILURE;
        }

        $prompt = $this->fillPrompt($prompt, [
            'categories_json' => $categories
        ]);

        $prompt = $this->fillPrompt($prompt, [
            'title' => $item->title,
            'content' => $item->content,
        ]);

        $aiModel = $this->getModel();

        if(!$aiModel) {
            return self::FAILURE;
        }

        $aiRequestLogId = null;
        $answer = $this->getChatAnswerAction->execute($prompt, $aiModel, true, $aiRequestLogId);

        $this->itemSetGlobalCategoryAction->execute($item, $answer, $aiRequestLogId);

        $isValidJson = $this->jsonValidator->isValid($answer);

        $this->line('Is valid JSON: '.($isValidJson ? 'Yes' : 'No'));
    }

    protected function getCategories($lang)
    {
        $categories = GlobalCategory::with('children:id,parent_id,name')
            ->where('language', $lang)
            ->whereNull('parent_id')
            ->select('id', 'name')
            ->orderBy('id', 'ASC')
            ->get();

        if ($categories->isEmpty()) {
            $this->warn('No categories found for language: '.$lang);

            return null;
        }

        $categories->each(function ($category) {
            $category->children->makeHidden('parent_id');
        });

        $json = $categories->toJson(JSON_UNESCAPED_UNICODE);

        return $json;
    }

    protected function getPrompt()
    {
        $prompt = $this->aiPromptTemplate->where('type', 'categorization')->inRandomOrder()->first();

        if(! $prompt) {
            $this->error('No prompt found for categorization.');

            return null;
        }

        $promptText = $prompt->user_prompt;
        

        return $promptText;
    }

    protected function fillPrompt($prompt, $variables)
    {
        foreach($variables as $key => $value) {
            $prompt = str_replace('{{'.$key.'}}', $value, $prompt);
        }

        return $prompt;
    }

    protected function getItem()
    {
        $item = $this->feedItem->where('is_category_checked', false)->orderBy('id', 'DESC')->first();

        if(!$item) {
            $this->info('No unchecked items found.');

            return null;
        }

        return $item;
    }

    protected function getChatResponse($prompt, $model)
    {
        $response = $this->getChatAnswerAction->execute($prompt, $model, true);

        if(!$response) {
            $this->error('No response from AI model.');

            return null;
        }

        $this->line('AI response: '.$response);

        return $response;
    }

    protected function getModel()
    {
        $model = AiModel::where('type', 'instruct')->with('provider')->inRandomOrder()->first();

        if(!$model) {
            $this->error('No chat model found.');

            return null;
        }

        return $model;
    }
}
