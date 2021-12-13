<?php

namespace App\Http\Controllers;

use App\Modules\Applications\Models\Application;
use App\Modules\Applications\Notification\TelegramNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class ApplicationController extends Controller
{
    public function storeMusicApplication(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
        ]);

        $application = new Application();
        $application->phone = $request->phone;

        $application->save();

        $application->notify(new TelegramNotification($application->phone));

        return compact('application');
    }
}
