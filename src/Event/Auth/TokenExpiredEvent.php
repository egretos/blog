<?php

namespace App\Event\Auth;

use App\Entity\Auth\Token;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class TokenExpiredEvent
 * @package App\Event\Auth
 */
class TokenExpiredEvent extends Event
{
    /** @var Token $token */
    private $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }
}