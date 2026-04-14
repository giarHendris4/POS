<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled task to delete expired carts
Schedule::call(function () {
    \App\Models\Cart::where('expires_at', '<', now())->delete();
})->hourly();
