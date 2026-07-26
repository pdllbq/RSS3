<?php

namespace App\Models\Ai;

use Illuminate\Database\Eloquent\Model;


class AiModel extends Model
{
    protected $fillable = [
        'ai_model_id',
        'ai_provider_id',
        'name',
        'slug',
        'provider_model_id',
        'type',
        'price_input',
        'price_output',
        'context_window',
        'embedding_dimensions',
        'config',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
        ];
    }

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function requestLogs()
    {
        return $this->hasMany(AiRequestLog::class);
    }
}
