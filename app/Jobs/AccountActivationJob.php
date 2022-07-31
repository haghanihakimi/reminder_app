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
use App\Notifications\AccountActivation;
use App\Events\NotificationsPops;
use Carbon\Carbon;

class AccountActivationJob implements ShouldQueue
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
        $url = $this->url_generator();

        $data = [
            "header" => "Hi ".$this->user->fname.",<br/>Please follow below link to verify your account.",
            "button" => "Verify Email Address",
            "link" => $url,
        ];

        $message = "Activation link is sent to your email address.";

        if (!$this->user->hasVerifiedEmail()) {
            Notification::send($this->user, new AccountActivation($this->user, 'email verification link sent', $message, "account", $data, now()));
            
            event(new NotificationsPops($this->user, 'You requested email verification link. Please check your email to verify your email.'));
        }
    }

    private function url_generator()
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 30)),
            [
                'id' => $this->user->getKey(),
                'hash' => sha1($this->user->getEmailForVerification())
            ]
        );
    }
}
