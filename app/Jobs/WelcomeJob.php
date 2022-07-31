<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use App\Notifications\WelcomeNotification;
use Carbon\Carbon;

class WelcomeJob implements ShouldQueue
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
            "header" => "Hello ".$this->user->fname.",<br/>Thank you so much for creating account with us.<br/>
            Please note, your account doesn't have appropriate password when you sign with Google or GitHub!<br/>
            Please change your password as soon as possible.",
            "button" => "Login to your account",
            "link" => route('root'),
        ];

        $message = "New account welcome message.";

        if (!$this->user->hasVerifiedEmail()) {
            Notification::send($this->user, new WelcomeNotification($this->user, 'welcome to your new account!', $message, "account", $data, now()));
        }
    }
}
