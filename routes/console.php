<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Schedule::command('feed:sync')->hourlyAt(42)->withoutOverlapping();

Schedule::command('items:generate-embedding')->everyMinute()->withoutOverlapping();

Schedule::command('items:match-cluster')->everyMinute()->withoutOverlapping();

Schedule::command('item:classify')->everyTwoMinutes()->withoutOverlapping();