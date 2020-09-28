<?php

namespace Core;

use App\Model\User;
use Core\Data\DataObject;

class Session extends DataObject
{
    private static $instance;
    private $currentUser;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->start();
    }

    public function __set($key, $value)
    {
        parent::__set($key, $value);
        $_SESSION[$key] = $value;
    }

    public static function getInstance(): self
    {
        if (static::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function start()
    {
        foreach ($_SESSION as $key => $data) {
            $k = 'set' . ucfirst($key);
            $this->$k($data);
        }

        return $this;
    }

    public function login(User $user): void
    {
        if ($user->getId()) {
            $_SESSION['user_id'] = $user->getId();
            $this->currentUser = $user;
        }
    }

    public function isLoggedIn(): bool
    {
        return !isset($this->currentUser);
    }

    public function end(): void
    {
        $_SESSION = array();
        session_destroy();
    }

    public static function unsetFormData()
    {
        unset($_SESSION['data']);
        unset($_SESSION['errors']);
    }
}
