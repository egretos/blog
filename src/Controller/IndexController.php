<?php

namespace App\Controller;

class IndexController extends WebController
{
    public function index()
    {
        //$this->auth->login('egretos@outlook.com', '111111');
        //$this->auth->logout();

        //var_dump($this->auth->isAuthenticated());

        return $this->render('index.html.twig');
    }
}