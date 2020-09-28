<?php

namespace App\Controller;

use App\Validation\LoginValidator;
use Core\Input;
use Core\Session;

class LoginController extends AbstractController
{
    private $session;

    public function __construct()
    {
        $this->session = Session::getInstance();

        // Clear errors and data if they exist for next validation
        $this->session::unsetFormData();
    }

    public function loginAction()
    {
        $this->redirect("#login");
    }

    public function loginSubmitAction()
    {
        $validator = new LoginValidator();
        $post = Input::validatePost();

        if ($validator->validate($post))
        {
            $this->redirect('#profile');
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setData($validator->getData());
            $this->session->setErrors($validator->getErrors());
            $this->redirect('#login');
        }
    }

    public function logoutAction()
    {

    }
}
