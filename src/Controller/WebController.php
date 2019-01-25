<?php

namespace App\Controller;

use App\Service\Auth\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class WebController
 * @package App\Controller
 *
 * @property Auth $auth
 */
abstract class WebController extends AbstractController
{
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

}