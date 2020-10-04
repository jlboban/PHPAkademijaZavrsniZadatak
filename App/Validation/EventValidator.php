<?php

declare(strict_types = 1);

namespace App\Validation;

class EventValidator extends AbstractValidator
{
    public function validate(array $data)
    {
        $this->validateName($data['name']);
        $this->validateDescription($data['description']);
        $this->validateEventMusician($data['musician']);
        $this->validateEventVenue($data['venue']);

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
                $this->errors['name'] = "Event name is too long.";
            }
        }
        else
        {
            $this->errors['name'] = "You must provide an event name.";
        }

        if (empty($this->errors['name']))
        {
            $this->data['name'] = $name;
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

    private function validateEventMusician($eventMusician)
    {
        if (empty($eventMusician))
        {
            $this->errors['musician'] = "You must select a musician.";
        }
    }

    private function validateEventVenue($eventVenue)
    {
        if (empty($eventVenue))
        {
            $this->errors['venue'] = "You must select a venue.";
        }
    }
}