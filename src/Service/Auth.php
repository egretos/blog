<?php

namespace App\Service;

use App\Entity\Auth\Token;
use App\Entity\User;
use App\Event\Auth\LoginEvent;
use App\Repository\Auth\IUserRepository;
use App\Repository\Auth\TokenRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

/**
 * Class Auth
 * @package App\Service\Auth
 *
 * @property Request $request
 * @property SessionInterface $sessionService
 *
 * @property User $user
 * @property IUserRepository $userRepository
 * @property EventDispatcherInterface $eventDispatcher
 * @property TokenRepository $tokenRepository
 */
class Auth
{
    private $request;

    private $sessionService;
    private $eventDispatcher;

    private $user = null;
    private $userRepository;
    private $tokenRepository;

    public function __construct(
        IUserRepository $userRepository,
        EventDispatcherInterface $eventDispatcher,
        TokenRepository $tokenRepository,
        SessionInterface $sessionService
    ) {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenRepository = $tokenRepository;
        $this->sessionService = $sessionService;
    }

    public function login($email, $password)
    {
        if (!$user = $this->userRepository->getByEmail($email)) {
            return false;
        }

        if ($user->verifyPassword($password)) {
            $this->eventDispatcher->dispatch('auth.user_login', new LoginEvent($user));
            $this->createToken();
            return true;
        } else {
            return false;
        }
    }

    public function createToken()
    {
        if (!$this->sessionService->isStarted()) {
            $this->sessionService->start();
        }
        $token = new Token;
        $token->generate(strtotime('+15 minutes'));

        var_dump($token);

        $this->tokenRepository->create($token);

        $this->sessionService->save();
    }

    public function logout($user)
    {
        if (!$this->sessionService->isStarted()) {
            $this->sessionService->start();
        }

        // TODO clear session here

        $this->sessionService->save();
    }

    public function register(User $user)
    {
        $this
            ->userRepository
            ->create($user);
    }
}