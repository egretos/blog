<?php

namespace App\Service\Auth;

use App\Entity\User;
use App\Repository\Person\IUserRepository;
use Doctrine\Common\Persistence\ObjectRepository;
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
 */
class Auth
{
    private $request;

    private $sessionService;

    private $user = null;
    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        $this->request = Request::createFromGlobals();

        $storage = new NativeSessionStorage([], new NativeFileSessionHandler());
        $this->sessionService = new Session($storage);

        $this->login();
    }

    public function login()
    {
        if (!$this->sessionService->isStarted()) {
            $this->sessionService->start();
        }

        $this->sessionService->save();
    }

    public function register(User $user)
    {

    }
}