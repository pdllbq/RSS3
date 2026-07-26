<?php
namespace App\Domain\Ai\Infrastructures;

use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

class OpenRouterEmbeddingClient
{

    function __construct()
    {
        
    }

    public function generateEmbedding($text, $model)
    {
        $timeout = (int) env('OPENROUTER_TIMEOUT', 300);

        $response = Embeddings::for([$text])
            ->timeout($timeout)
            ->generate(
                provider: Lab::OpenRouter,
                model: $model->provider_model_id,
            );
        return $response->embeddings[0];
    }
}