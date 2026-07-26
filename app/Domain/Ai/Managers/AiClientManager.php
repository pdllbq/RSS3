<?php
namespace App\Domain\Ai\Managers;

use App\Domain\Ai\Infrastructures\OpenRouterChatClient;
use App\Domain\Ai\Infrastructures\OpenRouterEmbeddingClient;

class AiClientManager
{

    function __construct(
        protected OpenRouterEmbeddingClient $openRouterEmbeddingClient,
        protected OpenRouterChatClient $openRouterChatClient
    ){}

    public function getClient($text, $model, bool $checkJson = false, ?int &$aiRequestLogId = null)
    {
        if ($model->provider->name == 'OpenRouter' && $model->type == 'embedding') {
            return $this->openRouterEmbeddingClient->generateEmbedding($text, $model);
        }

        if ($model->provider->name == 'OpenRouter' && ($model->type == 'chat' || $model->type == 'instruct')) {
            return $this->openRouterChatClient->generateChatResponse($text, $model, $checkJson, $aiRequestLogId);
        }
    }
}