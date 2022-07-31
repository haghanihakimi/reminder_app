<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use App\Notifications\RemindersNewAccount;
use Carbon\Carbon;

class NewAccountJob implements ShouldQueue
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
        $url = $this->url_generator($this->user);

        $data = [
            "header" => "Hi ".$this->user->fname.",<br/>Thank you for creating new account with us.<br/>Please verify your email to remove all restrictions.",
            "button" => "Verify Email Address",
            "link" => $url,
        ];

        $message = "Activation link is sent to your email address.";

        if (!$this->user->hasVerifiedEmail()) {
            Notification::send($this->user, new RemindersNewAccount($this->user, 'account verification', $message, "account", $data, now()));
            return back()->with("success", "Verfication is sent to your email address");
        }
    }

    private function url_generator($user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 30)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification())
            ]
        );
    }
}
