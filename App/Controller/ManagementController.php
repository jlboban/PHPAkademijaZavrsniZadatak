<?php

declare(strict_types = 1);

namespace App\Controller;

class ManagementController extends AbstractController
{
    public function managementAction()
    {
        $this->view->render("Admin/Management");
    }
}
