<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Schedule::command('feed:sync')->hourly();

Schedule::command('items:generate-embedding')->everyMinute();

Schedule::command('items:match-cluster')->everyMinute();

Schedule::command('item:classify')->everyTwoMinutes();