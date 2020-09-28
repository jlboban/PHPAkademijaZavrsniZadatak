<?php

declare(strict_types = 1);

namespace App\Validation;

use App\Model\User;

class LoginValidator implements ValidatorInterface
{
    private $data = [];
    private $errors = [];

    public function validate(array $data): bool
    {
        $this->validateEmail($data['email']);
        $this->validatePassword($data['password']);

        return !array_filter($this->errors) ? $isValid = true : $isValid = false;
    }

    private function validateEmail(string $email): void
    {
        if (!empty($email))
        {
            $this->data['email'] = $email;
        }
        else
        {
            $this->errors['email'] = "You must enter an email.";
        }
    }

    private function validatePassword(string $password): void
    {
        if (!empty($password))
        {
            $user = User::getOne('email', $this->data['email']);
            $hashedPassword = $user->getPassword();

            if ($hashedPassword === null || !password_verify($password, $hashedPassword))
            {
                $this->errors['login'] = "Wrong username/password combination.";
            }
        }
        else
        {
            $this->errors['login'] = "You must enter a password.";
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
