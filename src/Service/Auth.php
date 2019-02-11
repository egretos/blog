<?php

namespace App\Service;

use App\Entity\Auth\Token;
use App\Entity\User;
use App\Event\Auth\LoginEvent;
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
    private $sessionAuthUser = 'auth_user';

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

    public function createToken($user = null)
    {
        $token = new Token;
        $token->generate();

        $this->tokenRepository->create($token, $user);

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

        $this->sessionService->set($this->sessionTokenKey, (string) $token);
        $this->sessionService->set($this->sessionAuthUser, json_encode($user));

        return $user;
    }

    public function logout()
    {
        if (!$this->sessionService->isStarted()) {
            $this->sessionService->start();
        }

        $this->sessionService->remove($this->sessionTokenKey);
        $this->sessionService->remove($this->sessionAuthUser);

        $this->sessionService->save();
    }

    public function register(User $user)
    {
        $this
            ->userRepository
            ->create($user);
    }
}