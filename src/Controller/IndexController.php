<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function index()
    {
        return new Response('This is working!');
    }

}