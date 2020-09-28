<?php

declare(strict_types = 1);

namespace App\Controller;

use Core\View;

class AbstractController
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    protected function redirect(string $location)
    {
        $location = strtolower($location);

        header('Location: ' . URL_ROOT . $location);
    }
}
