<?php

declare(strict_types = 1);

namespace App\Validation;

class VenueValidator extends AbstractValidator
{
    function validate(array $data): bool
    {
        $this->validateName($data['name']);
        $this->validateCapacity($data['capacity']);
        $this->validateAddress($data['address']);
        $this->validateCity($data['city']);
        $this->validatePostcode($data['postcode']);
        $this->validateCountry($data['country']);

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
                $this->errors['name'] = "Venue name is too long.";
            }
        }
        else
        {
            $this->errors['name'] = "You must provide a venue name.";
        }

        if (empty($this->errors['name']))
        {
            $this->data['name'] = $name;
        }
    }

    private function validateCapacity(string $capacity)
    {
        if (!empty($capacity))
        {
            if (strlen($capacity) < 2 || strlen($capacity) > 6 || !ctype_digit($capacity))
            {
                $this->errors['capacity'] = "Please input a valid capacity number.";
            }
        }
        else
        {
            $this->errors['capacity'] = "Please input a capacity value.";
        }

        if (empty($this->errors['capacity']))
        {
            $this->data['capacity'] = $capacity;
        }
    }

    private function validateAddress(string $address): void
    {
        if (!empty($address))
        {
            if (strlen($address) < 2 || strlen($address) > 50)
            {
                $this->errors['address'] = "Please input a valid address.";
            }
        }
        else
        {
            $this->errors['address'] = "Please input an address.";
        }

        if (empty($this->errors['address']))
        {
            $this->data['address'] = $address;
        }
    }

    private function validateCity(string $city): void
    {
        if (!empty($city))
        {
            if (strlen($city) < 2 || strlen($city) > 50)
            {
                $this->errors['city'] = "Please input a valid city name.";
            }
        }
        else
        {
            $this->errors['city'] = "Please input a city name.";
        }

        if (empty($this->errors['city']))
        {
            $this->data['city'] = $city;
        }
    }

    private function validatePostcode(string $postcode): void
    {
        if (!empty($postcode))
        {
            if (strlen($postcode) < 2 || strlen($postcode) > 10)
            {
                $this->errors['postcode'] = "Please input a valid postal code.";
            }
        }
        else
        {
            $this->errors['postcode'] = "Please input a postcode.";
        }

        if (empty($this->errors['postcode']))
        {
            $this->data['postcode'] = $postcode;
        }
    }

    private function validateCountry(string $country): void
    {
        if (!empty($country))
        {
            if (strlen($country) < 2 || strlen($country) > 50)
            {
                $this->errors['country'] = "Please input a valid country name.";
            }
        }
        else
        {
            $this->errors['country'] = "Please input a country.";
        }

        if (empty($this->errors['country']))
        {
            $this->data['country'] = $country;
        }
    }
}
