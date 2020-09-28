<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\User;
use App\Validation\RegisterValidator;
use Core\Input;
use Core\Session;

class RegisterController extends AbstractController
{
    private $session;

    public function __construct()
    {
        $this->session = Session::getInstance();

        // Clear errors and data if they exist for next validation
        $this->session::unsetFormData();
    }

    public function registerAction()
    {
        $this->redirect('#register');
    }

    public function registerSubmitAction()
    {
        $validator = new RegisterValidator();
        $post = Input::validatePost();

        if ($validator->validate($post))
        {
            // Formatting
            // Example: jOHN to John
            //          EMAil@emAIL.COm to email@email.com
            $firstName = ucfirst(strtolower($post['first-name']));
            $lastName = ucfirst(strtolower($post['last-name']));
            $email = strtolower($post['email']);
            $password = password_hash($post['password'], PASSWORD_DEFAULT);

            User::insert([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => $password
            ]);

            $this->redirect('#login');
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setData($validator->getData());
            $this->session->setErrors($validator->getErrors());
            $this->redirect('#register');
        }
    }
}
