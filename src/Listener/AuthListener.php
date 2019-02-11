<?php

namespace App\Listener;

use App\Event\Auth\LoginEvent;
use App\Service\Auth;

/**
 * Class AuthListener
 * @package App\Listener
 */
class AuthListener
{
    private $authService;

    public function __construct(Auth $authService)
    {
        $this->authService = $authService;
    }

    // TODO make it async
    public function createToken(LoginEvent $event)
    {
        $this->authService->updateToken($event->getUser());
    }
}