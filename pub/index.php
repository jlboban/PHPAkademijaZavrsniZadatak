<?php

require_once '../App/Config.php';
require_once '../Core/Autoloader.php';

use Core\Autoloader;
use App\Application;

Autoloader::init();
session_start();

$app = new Application();
$app->start();

var_dump($_SERVER['REQUEST_URI']);
var_dump($_SERVER['PATH_INFO']);
