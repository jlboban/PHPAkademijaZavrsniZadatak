<?php

declare(strict_types = 1);

namespace App\Controller;

class PagesController extends AbstractController
{
    public function indexAction(): void
    {
        $this->view->render('Pages/Index');
    }

    public function eventsAction(): void
    {
        $this->view->render('Pages/Venue');
    }

    public function musiciansAction(): void
    {
        $this->view->render('Pages/Musicians');
    }

    public function notFoundAction(): void
    {
        $this->view->render('Pages/404');
    }

    public function serverErrorAction(): void
    {
        $this->view->render('Pages/500');
    }
}
