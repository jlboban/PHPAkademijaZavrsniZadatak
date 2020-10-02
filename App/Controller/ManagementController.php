<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\User;

class ManagementController extends AbstractController
{
    public function managementAction()
    {
        $this->view->render("Admin/Management");
    }

    public function adminListAction()
    {

    }
}