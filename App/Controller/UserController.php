<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\User;
use App\Validation\UserValidator;
use Core\Input;

class UserController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listAction()
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $this->view->render("Admin/User/List", [
            'users' => User::getMultiple('is_admin', 0)
        ]);
    }

    public function settingsAction()
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $userId = $this->auth->getCurrentUser()->getId();

        $this->view->render("User/Settings", [
            'user' => User::getOne('id', $userId)
        ]);
    }

    public function editSubmitAction($id)
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $validator = new UserValidator();
        $post = Input::validatePost();

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();

        if ($validator->validate($post))
        {
            $address = ucfirst(strtolower($post['address']));
            $city = ucfirst(strtolower($post['city']));
            $postcode = $post['postcode'];
            $country = ucfirst(strtolower($post['country']));

            User::update([
                'address' => $address,
                'city' => $city,
                'postcode' => $postcode,
                'country' => $country
            ], 'id', $id);

            $this->redirect('user/settings');
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setFormData($validator->getData());
            $this->session->setFormErrors($validator->getErrors());

            $this->redirect('user/settings');
        }
    }

    public function editPasswordSubmitAction($id)
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $validator = new UserValidator();
        $post = Input::validatePost();

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();

        if ($validator->validateUpdatedPassword($post))
        {
            $password = password_hash($post['password'], PASSWORD_DEFAULT);

            User::update(['password' => $password], 'id', $id);

            $this->redirect('settings');
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setFormData($validator->getData());
            $this->session->setFormErrors($validator->getErrors());

            $this->redirect('settings');
        }
    }
}