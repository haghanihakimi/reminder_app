<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DeleteAccount;
use Carbon\Carbon;

class DeleteAccountJob implements ShouldQueue
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
            "header" => "Dear ".$this->user->fname.",<br/><br/>Your account is scheduled for permanent deletion.<br/><br/>Your account will be permanently deleted after 14 days.<br/>After ".Carbon::parse(now()->addDays(14))->format('D, m M Y').', you will not be able to login to your account.<br/><br/>All data linked to your account will be permanently deleted.<br/><br/>Login back to your account to reactivate it.',
            "button" => "Reactivate Account",
            "link" => "",
        ];

        $message = "Account deletion schedule.";

        Notification::send($this->user, new DeleteAccount($this->user, 'account deletion schedule', $message, "account", $data, now()));
    }
}
