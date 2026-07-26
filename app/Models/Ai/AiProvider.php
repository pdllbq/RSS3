<?php

namespace App\Models\Ai;

use Illuminate\Database\Eloquent\Model;

class AiProvider extends Model
{
    protected $fillable = [
        'ai_provider_id',
        'name',
        'slug',
        'base_url',
        'api_key',
        'is_active',
        'config',
    ];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'config' => 'array',
        ];
    }

    public function models()
    {
        return $this->hasMany(AiModel::class);
    }

    public function requestLogs()
    {
        return $this->hasMany(AiRequestLog::class);
    }
}
