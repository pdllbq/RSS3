<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Model;

class ItemEmbedding extends Model
{
    protected $fillable = [
        'feed_item_id',
        'model',
        'embedding_1024',
        'embedding_qwen3_8b_1536',
    ];

    protected $casts = [
        'embedding_1024' => 'array',
        'embedding_qwen3_8b_1536' => 'array',
    ];
}
