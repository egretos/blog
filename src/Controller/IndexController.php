<?php

namespace App\Controller;

class IndexController extends WebController
{
    public function index()
    {
        $this->auth->login('egretos@outlook.com', '111111');

        return $this->render('index.html.twig');
    }
}