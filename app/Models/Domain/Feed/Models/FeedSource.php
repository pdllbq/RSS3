<?php

namespace App\Models\Domain\Feed\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class FeedSource extends Model
{
    protected $fillable = [
        'title',
        'url',
        'site_url',
        'description',
        'language',
        'added_by',
        'user_id',
        'is_active',
        'last_fetched_at',
        'last_success_at',
        'last_error',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_fetched_at' => 'datetime',
        'last_success_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
