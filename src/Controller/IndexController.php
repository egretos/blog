<?php

namespace App\Controller;

class IndexController extends WebController
{
    public function index()
    {
        return $this->render('index.html.twig');
    }
}