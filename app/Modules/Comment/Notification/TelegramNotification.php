<?php

namespace App\Modules\Comment\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotification extends Notification
{
    use Queueable;

    private $channelId = "-1001482899342";

    private $post;
    private $postName;

    public function __construct($post, $postName)
    {
        $this->post = $post;
        $this->postName = $postName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        $url = env('FRONTEND_URL').'#/video/'.$this->post->id;
        return TelegramMessage::create()
            ->to($this->channelId)
            ->content("Новие коментарии на {$this->postName}  : {$url}");
    }
}
