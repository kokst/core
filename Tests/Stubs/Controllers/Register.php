<?php

namespace Kokst\Core\Tests\Stubs\Controllers;

use Closure;
use PHPUnit\Framework\Assert;

class Register extends \Kokst\Core\Http\Controllers\Auth\RegisterController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
