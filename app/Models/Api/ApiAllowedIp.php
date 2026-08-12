<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ApiAllowedIp extends Model
{
    protected $fillable = [
        'ip_address',
        'name',
        'is_active',
    ];
}
