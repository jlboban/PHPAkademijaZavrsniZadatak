<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\User;
use App\Validation\AdminValidator;
use Core\Input;

class AdminController extends AbstractController
{
    private const EMAIL_DOMAIN = "@eventzone.com";

    public function __construct()
    {
        parent::__construct();

        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }
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

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();
    }

    public function createSubmitAction()
    {
        $validator = new AdminValidator();
        $post = Input::validatePost();

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();

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
            $this->session->setFormData($validator->getData());
            $this->session->setFormErrors($validator->getErrors());
            $this->redirect('admin/create');
        }
    }

    public function editAction(string $id)
    {
        $this->view->render("Admin/Edit", [
            'admin' => User::getOne('id', $id)
        ]);

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();
    }

    public function editSubmitAction(string $id)
    {
        $validator = new AdminValidator();
        $post = Input::validatePost();

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();

        if ($validator->validateEdit($post))
        {
            $firstName = ucfirst(strtolower($post['first-name']));
            $lastName = ucfirst(strtolower($post['last-name']));
            $email = $this->parseAdminMail(strtolower($post['email']));

            User::update(['first_name' => $firstName, 'last_name' => $lastName, 'email' => $email], 'id', $id);

            $this->redirect('admin/list');
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setFormData($validator->getData());
            $this->session->setFormErrors($validator->getErrors());

            $this->redirect('admin/edit/' . $id);
        }

    }

    public function deleteSubmitAction($id)
    {
        User::delete('id', $id);
        $this->redirect('admin/list');
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
