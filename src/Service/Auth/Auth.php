<?php

namespace App\Service\Auth;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class Auth
 * @package App\Service\Auth
 *
 * @property Request $request
 */
class Auth
{
    private $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }
}