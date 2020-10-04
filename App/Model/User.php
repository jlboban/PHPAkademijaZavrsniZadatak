<?php

declare(strict_types = 1);

namespace App\Model;

use Core\Data\Database;

class User extends AbstractModel
{
    protected static $tableName = 'user';

    public static function isEmailAvailable($email): bool
    {
        $user = self::getOne('email', $email);

        return $user->getEmail() ? true : false;
    }

    public static function isBooked(string $userId): bool
    {
        $sql = "SELECT e.id FROM booking b
                INNER JOIN event e ON b.event_id = e.id
                WHERE b.user_id = $userId";

        $db = Database::getInstance();

        $statement = $db->prepare($sql);
        $statement->execute();

        $row = $statement->fetch();

        return $row != null ? true : false;
    }

    public static function isBookedToEvent(string $userId, string $eventId): bool
    {
        $sql = "SELECT e.id FROM booking b
                INNER JOIN event e ON b.event_id = e.id
                WHERE b.user_id = $userId AND b.event_id = $eventId";

        $db = Database::getInstance();

        $statement = $db->prepare($sql);
        $statement->execute();

        $row = $statement->fetch();

        return $row != null ? true : false;
    }
}
