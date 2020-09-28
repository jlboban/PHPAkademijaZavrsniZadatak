<?php

declare(strict_types = 1);

namespace Core\Data;

class DataObject
{
    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    public function __call($name, $arguments)
    {
        $function = substr($name, 0, 3);

        if ($function === 'set')
        {
            $this->__set(strtolower(substr($name, 3)), $arguments[0]);
            return $this;
        }
        else if ($function === 'get')
        {
            return $this->__get(strtolower(substr($name, 3)));
        }

        return $this;
    }
}
