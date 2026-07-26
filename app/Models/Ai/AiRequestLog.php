<?php

namespace App\Models\Ai;

use Illuminate\Database\Eloquent\Model;

class AiRequestLog extends Model
{
    protected $fillable = [
        'ai_model_id',
        'ai_provider_id',
        'status',
        'task',
        'prompt',
        'response',
        'tokens_input',
        'tokens_output',
        'tokens_total',
        'cost',
        'error_message',
        'request_payload',
        'response_payload',
        'messages',
        'embedding_1024',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'messages' => 'array',
    ];

    public function model()
    {
        return $this->belongsTo(AiModel::class, 'ai_model_id');
    }

    // Keep the old misspelled relation as an alias for compatibility
    public function provaider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }
}
