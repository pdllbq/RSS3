<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Schedule::command('feed:sync')->everyMinute()->appendOutputTo(storage_path('logs/feed-sync.log'));

Schedule::command('items:generate-embedding')->everyMinute();

Schedule::command('items:match-cluster')->everyMinute();

Schedule::command('item:classify')->everyTwoMinutes();