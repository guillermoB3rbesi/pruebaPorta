<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendEmailForUsersDisconnectedJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::whereDate('latest_login','<=',now()->subDays(30)->format('Y-m-d'))
            ->cursor()
            ->each(fn(User $user) => $user->sendEmailLatestLogin());
    }
}
