<?php

declare(strict_types = 1);

namespace App\Validation;

use App\Model\User;

class UserValidator extends AbstractValidator
{
    public function validate(array $data): bool
    {
        $this->validatePassword($data['password']);

        return empty($this->getErrors());
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
