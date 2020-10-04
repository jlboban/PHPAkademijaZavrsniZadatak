<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\User;

class UserController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listAction()
    {
        $this->view->render("Admin/User/List", [
            'users' => User::getMultiple('is_admin', 0)
        ]);
    }
}