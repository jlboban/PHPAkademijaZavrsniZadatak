<?php

declare(strict_types = 1);

namespace App\Controller;

class PagesController extends AbstractController
{
    public function indexAction()
    {
        echo __METHOD__;
    }

    public function badRequestAction()
    {
        echo '400';
    }

    public function notFoundAction()
    {
        echo '404';
    }

    public function serverErrorAction()
    {
        echo '500';
    }
}
