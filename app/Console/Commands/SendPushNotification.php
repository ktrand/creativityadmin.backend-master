<?php

namespace App\Console\Commands;

use App\Modules\Auth\Models\User;
use Illuminate\Console\Command;

class SendPushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => 'title',
                "body" => 'body',
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . config('app.firebase_server_key'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
    }
}
