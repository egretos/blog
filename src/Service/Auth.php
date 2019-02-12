<?php

namespace App\Service;

use App\Entity\Auth\Token;
use App\Entity\Auth\User;
use App\Event\Auth\LoginEvent;
use App\Event\Auth\TokenExpiredEvent;
use App\Repository\Auth\IUserRepository;
use App\Repository\Auth\TokenRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class Auth
 * @package App\Service\Auth
 *
 * @property integer $tokenLiveTime
 */
class Auth
{
    public $tokenLiveTime;

    private $sessionService;
    private $eventDispatcher;

    private $sessionTokenKey = 'auth_token';
    private $sessionUserKey = 'auth_user';

    private $userRepository;
    private $tokenRepository;

    public function __construct(
        IUserRepository $userRepository,
        EventDispatcherInterface $eventDispatcher,
        TokenRepository $tokenRepository,
        SessionInterface $sessionService
    ) {
        $this->tokenLiveTime = strtotime('+15 minutes');

        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenRepository = $tokenRepository;
        $this->sessionService = $sessionService;

        if (!$this->sessionService->isStarted()) {
            $this->sessionService->start();
        }
    }

    /**
     * @param $email
     * @param $password
     *
     * @return bool
     */
    public function login($email, $password)
    {
        if (!$user = $this->userRepository->getByEmail($email)) {
            return false;
        }

        if ($user->verifyPassword($password)) {
            $this->eventDispatcher->dispatch('auth.user_login', new LoginEvent($user));

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param null $user
     * @return Token
     */
    public function createToken($user = null)
    {
        $token = new Token;
        $token->generate();

        $this->tokenRepository->create($token, $user);

        return $token;
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        if ($this->sessionService->has($this->sessionTokenKey)) {
            $token = $this->getTokenFromSession();

            if (!$token->isExpired()) {
                return true;
            }
            $this->eventDispatcher->dispatch('auth.token_expired', new TokenExpiredEvent($token));
        }
        return false;
    }

    /**
     * @return User|null
     */
    public function user()
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return $this->getUserFromSession();
    }

    /**
     * @return User
     */
    public function getUserFromSession()
    {
        $user = new User;
        $user->load(json_decode($this->sessionService->get($this->sessionUserKey), true));
        return $user;
    }

    /**
     * @return Token
     */
    public function getTokenFromSession()
    {
        $token = new Token;
        $token->load(json_decode($this->sessionService->get($this->sessionTokenKey), true));
        return $token;

    }

    /**
     * @param User $user
     * @return User
     *
     * @throws
     */
    public function updateToken(User $user)
    {
        if (!$user->hasAuthToken()) {
            $token = $this->createToken($user);
        } else {
            $token = $user->getToken();
        }
        $token->expireAt = $this->tokenLiveTime;
        $this->tokenRepository->update($token);

        $this->sessionService->set($this->sessionTokenKey, json_encode($token));
        $this->sessionService->set($this->sessionUserKey, json_encode($user));

        return $user;
    }

    public function logout()
    {
        $this->sessionService->remove($this->sessionTokenKey);
        $this->sessionService->remove($this->sessionUserKey);
    }

    public function register(User $user)
    {
        $this
            ->userRepository
            ->create($user);
    }
}