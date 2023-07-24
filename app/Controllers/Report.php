<?php

namespace App\Controllers;

class Report extends BaseController
{
    public function index()
    {
        return $this->twig->render('report/index.html', ['baseUrl' => base_url()]);
    }

    public function compare()
    {
        return $this->twig->render('report/compare.html', ['baseUrl' => base_url()]);
    }
}
