<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class WelcomeNotification extends Notification
{
    use Queueable;

    private $user;
    private $title;
    private $message;
    private $data;
    private $type;
    private $readStatus;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $title, $message, $type, $data, $readStatus)
    {
        $this->user = $user;
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
        $this->readStatus = $readStatus;
    }

    public function getUser () {
        return $this->user;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getReadStatus () {
        return $this->readStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line(new HtmlString($this->data['header']))
            ->action($this->data['button'], $this->data['link']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'target_id' => $this->id
        ];
    }
}
