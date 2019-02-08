<?php

namespace App\Event\Auth;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class LoginEvent
 * @package App\Event\Auth
 */
class LoginEvent extends Event
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}