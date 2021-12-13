<?php

namespace App\Modules\Applications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Application extends Model
{
    use Notifiable;
    protected $guarded = [];
}
