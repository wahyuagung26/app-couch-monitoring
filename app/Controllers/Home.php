<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return $this->twig->render('submit/index.html', ['baseUrl' => base_url()]);
    }
}
