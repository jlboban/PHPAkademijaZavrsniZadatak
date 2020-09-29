<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\User;
use App\Validation\AdminValidator;
use Core\Input;
use Core\Session;

class AdminController extends AbstractController
{
    private const EMAIL_DOMAIN = "@eventzone.com";

    private $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = Session::getInstance();

        // Clear errors and data if they exist for next validation
        $this->session::unsetFormData();
    }

    public function listAction()
    {
        $this->view->render("Admin/List", [
            'admins' => User::getMultiple('is_admin', '1')
        ]);
    }

    public function createAction()
    {
        $this->view->render("Admin/Create");
    }

    public function createSubmitAction()
    {
        $validator = new AdminValidator();
        $post = Input::validatePost();

        if ($validator->validate($post))
        {
            $firstName = ucfirst(strtolower($post['first-name']));
            $lastName = ucfirst(strtolower($post['last-name']));
            $email = $this->parseAdminMail(strtolower($post['email']));
            $password = password_hash($post['password'], PASSWORD_DEFAULT);

            User::insert([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => $password,
                'is_admin' => '1'
            ]);

            $this->redirect('admin/list');
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setData($validator->getData());
            $this->session->setErrors($validator->getErrors());
            $this->redirect('admin/create');
        }
    }

    public function editSubmit()
    {

    }

    public function deleteAction()
    {

    }

    public function parseAdminMail($email)
    {
        $parts = explode('@', $email);
        $localPart = $parts[0];
        $domain = $parts[1];

        if ($domain !== self::EMAIL_DOMAIN)
        {
            $domain = self::EMAIL_DOMAIN;
        }

        return $email = $localPart . $domain;
    }
}
