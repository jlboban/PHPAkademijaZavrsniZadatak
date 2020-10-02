<?php

declare(strict_types = 1);

namespace App\Validation;

class AdminValidator extends AbstractValidator
{
    public function validate(array $data): bool
    {
        $this->validateName($data['first-name'], $data['last-name']);
        $this->validateEmail($data['email']);
        $this->validatePassword($data['password']);

        return empty($this->getErrors());
    }

    public function validateEdit(array $data): bool
    {
        $this->validateName($data['first-name'], $data['last-name']);
        $this->validateEmail($data['email']);

        return empty($this->getErrors());
    }

    private function validateName(string $firstName, string $lastName): void
    {
        if (!empty($firstName) || !empty($lastName))
        {
            if (strlen($firstName) < 2 || strlen($lastName) < 2)
            {
                $this->errors['name'] = "Please enter a real name.";
            }
            elseif (strlen($firstName) > 50)
            {
                $this->errors['name'] = "Name is too long.";
            }
        }
        else
        {
            $this->errors['name'] = "You must enter your full name.";
        }

        if (empty($this->errors['name']))
        {
            $this->data['firstName'] = $firstName;
            $this->data['lastName'] = $lastName;
        }
    }

    private function validateEmail(string $email): void
    {
        if (!empty($email))
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $this->errors['email'] = "Invalid email provided.";
            }

            if (strlen($email) < 2)
            {
                $this->errors['email'] = "Email is too short.";
            }

            if (strlen($email) > 50)
            {
                $this->errors['email'] = "Email is too long.";
            }

            if (empty($this->errors['email']))
            {
                $this->data['email'] = $email;
            }
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
            if (strlen($password) < 8)
            {
                $this->errors['password'] = "Password must be at least 8 characters long.";
            }
            if (strlen($password) > 50)
            {
                $this->errors['password'] = "Password must be less than 50 characters long.";
            }
            if (!preg_match("#[0-9]+#", $password))
            {
                $this->errors['password'] = "Password must have at least 1 number.";
            }
            if (!preg_match("#[A-Z]+#", $password))
            {
                $this->errors['password'] = "Password must have at least 1 uppercase character.";
            }
            if (!preg_match("#[a-z]+#", $password))
            {
                $this->errors['password'] = "Password must have at least 1 lowercase character.";
            }
        }
        else
        {
            $this->errors['password'] = "You must enter a password.";
        }
    }
}
