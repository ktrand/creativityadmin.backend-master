<?php

namespace App\Modules\Comment\Jobs;

use App\Modules\Comment\Notification\TelegramNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $post;
    private $postName;

    /**
     * SendNotification constructor.
     * @param $post
     * @param $postName
     */
    public function __construct($post, $postName)
    {
        $this->post = $post;
        $this->postName = $postName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->post->notify(new TelegramNotification($this->post, $this->postName));
    }
}
