<?php

namespace App\Controller;

use App\Service\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class WebController
 * @package App\Controller
 *
 * @property Auth $auth
 */
abstract class WebController extends AbstractController
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
}