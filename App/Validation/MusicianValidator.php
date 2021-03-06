<?php

declare(strict_types = 1);

namespace App\Validation;

class MusicianValidator extends AbstractValidator
{
    function validate(array $data): bool
    {
        $this->validateName($data['name']);
        $this->validateGenre($data['genre']);
        $this->validateDescription($data['description']);

        return empty($this->getErrors());
    }

    private function validateName(string $name): void
    {
        if (!empty($name))
        {
            if (strlen($name) < 2)
            {
                $this->errors['name'] = "Please enter a valid name.";
            }
            elseif (strlen($name) > 50)
            {
                $this->errors['name'] = "Musician name is too long.";
            }
        }
        else
        {
            $this->errors['name'] = "You must provide a musician name.";
        }

        if (empty($this->errors['name']))
        {
            $this->data['name'] = $name;
        }
    }

    private function validateGenre(string $genre): void
    {
        if (!empty($genre))
        {
            if (strlen($genre) < 2)
            {
                $this->errors['$genre'] = "Please enter a valid genre name.";
            }
            elseif (strlen($genre) > 50)
            {
                $this->errors['$genre'] = "Genre is too long.";
            }
        }
        else
        {
            $this->errors['genre'] = "You must provide a genre name.";
        }

        if (empty($this->errors['genre']))
        {
            $this->data['genre'] = $genre;
        }
    }

    private function validateDescription($description)
    {
        if (empty($description))
        {
            $this->errors['description'] = "You must provide a description.";
        }

        if (empty($this->errors['description']))
        {
            $this->data['description'] = $description;
        }
    }
}
