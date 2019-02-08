<?php

namespace App\Listener;

use App\Event\Auth\LoginEvent;
use App\Repository\Auth\TokenRepository;

/**
 * Class AuthListener
 * @package App\Listener
 */
class AuthListener
{
    public function createToken(LoginEvent $event)
    {

    }
}