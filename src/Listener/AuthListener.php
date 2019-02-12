<?php

namespace App\Listener;

use App\Event\Auth\LoginEvent;
use App\Event\Auth\TokenExpiredEvent;
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

    public function logout()
    {
        $this->authService->logout();
    }
}