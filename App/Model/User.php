<?php

declare(strict_types = 1);

namespace App\Model;

class User extends AbstractModel
{
    protected static $tableName = 'user';

    public static function isEmailAvailable($email): bool
    {
        $user = self::getOne('email', $email);

        return $user->getEmail() ? true : false;
    }
}
