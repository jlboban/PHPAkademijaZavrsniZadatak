<?php

declare(strict_types = 1);

namespace App\Validation;

class ImageValidator extends AbstractValidator
{
    private $allowedExtensions = [
        'jpg',
        'jpeg',
        'png'
    ];

    function validate(array $file)
    {
        $this->validateImage($file);
        $this->validateSize($file['image']['size']);
        $this->validateExtension($file['image']['type']);

        return empty($this->getErrors());
    }

    private function validateSize(string $size) : void
    {
        // 100 kB
        if ($size > 100000)
        {
            $this->errors['image'] = "Image is too large.";
        }
    }

    private function validateExtension(string $type) : void
    {
        // Lowercase and get extension out of file type
        $tmp = explode('/', $type);
        $ext = strtolower(end($tmp));

        if (!in_array($ext, $this->allowedExtensions))
        {
            $this->errors['image'] = "File type not allowed.";
        }
    }

    private function validateImage(?array $file)
    {
        if (empty($file) || $file['image']['error'] == 1)
        {
            $this->errors['image'] = "Please input an image.";
        }
    }
}
