<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Jobs\SendEmailForUsersDisconnectedJob
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new SendEmailForUsersDisconnectedJob())->daily()
    ->when(fn() => User::whereDate('latest_login','<=',now()->subDays(30)->format('Y-m-d'))->exists() );
