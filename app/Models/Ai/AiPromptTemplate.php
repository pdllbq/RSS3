<?php

namespace App\Models\Ai;

use Illuminate\Database\Eloquent\Model;

class AiPromptTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'task',
        'system_prompt',
        'user_prompt',
        'model_variables',
        'config',
        'version',
        'is_active',
    ];

    protected $casts = [
        'model_variables' => 'array',
        'config' => 'array',
        'is_active' => 'boolean',
    ];
}
