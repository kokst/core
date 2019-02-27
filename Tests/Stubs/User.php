<?php

namespace Kokst\Core\Tests\Stubs;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Eloquent;

class User extends Eloquent
{
    use Notifiable;
}
