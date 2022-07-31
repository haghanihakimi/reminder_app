<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordResetAlert;
use Carbon\Carbon;

class PasswordResetAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            "header" => "Hi ".$this->user->fname.",<br/>You recently changed password of your account successfully.",
        ];

        $message = "Password Reset Alert";

        Notification::send($this->user, new PasswordResetAlert($this->user, 'your password is recently changed', $message, "account", $data, now()));
    }
}
