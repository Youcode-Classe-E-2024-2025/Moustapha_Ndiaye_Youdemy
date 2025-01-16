<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // load view "home.php"
        $this->view('home');
    }

    // methode loading
    protected function view($view, $data = [])
    {
        // Inclure "views"
        include_once __DIR__ . '/../views/' . $view . '.php';
    }
}