<?php

declare(strict_types = 1);

namespace App\Model;

use Core\Data\Database;

class Event extends AbstractModel
{
    protected static $tableName = 'event';

    public static function getEventMusicians($id)
    {
        $sql = "SELECT m.id, m.name, m.genre, m.description FROM musician m
                INNER JOIN event_musician em ON m.id = em.musician_id
                INNER JOIN event e ON em.event_id = e.id
                WHERE e.id = $id";

        $db = Database::getInstance();

        $statement = $db->prepare($sql);
        $statement->execute();

        $models = [];
        while ($row = $statement->fetch())
        {
            $models[] = static::createObject($row);
        }

        return $models;
    }

    public static function getEventVenues($id)
    {
        $sql = "SELECT v.id, v.name, v.capacity, v.address, v.city, v.postcode, v.country FROM venue v
        INNER JOIN event_venue ev on v.id = ev.venue_id
        INNER JOIN event e on ev.event_id = e.id
        WHERE event_id = $id;";

        $db = Database::getInstance();

        $statement = $db->prepare($sql);
        $statement->execute();

        $models = [];
        while ($row = $statement->fetch())
        {
            $models[] = static::createObject($row);
        }

        return $models;
    }

    public static function addMusicianToEvent(string $eventId, string $musicianId)
    {
        $sql = "INSERT INTO event_musician(event_id, musician_id) VALUES ({$eventId}, {$musicianId})";

        $db = Database::getInstance();

        $statement = $db->prepare($sql);
        $statement->execute();
    }

    public static function addVenueToEvent(string $eventId, string $venueId)
    {
        $sql = "INSERT INTO event_venue(event_id, venue_id) VALUES ({$eventId}, {$venueId})";

        $db = Database::getInstance();

        $statement = $db->prepare($sql);
        $statement->execute();
    }
}
