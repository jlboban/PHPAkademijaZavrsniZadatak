<?php

namespace Core;

class Autoloader
{
    public static function init()
    {
        spl_autoload_register(function (string $class)
        {
            $file = BASE_ROOT . str_replace('\\', DS, $class) . '.php';

            if (file_exists($file))
            {
                require $file;
            }
        });
    }
}
