<?php

declare(strict_types=1);

namespace Core;

class Route
{
    public $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function __construct()
    {
        $this->register('GET', '/', 'Pages@index');
        $this->register('GET', 'events', 'Pages@events');
        $this->register('GET', 'musicians', 'Pages@musicians');
        $this->register('GET', '400', 'Pages@invalidRequest');
        $this->register('GET', '404', 'Pages@notFound');
        $this->register('GET', '500', 'Pages@serverError');

        $this->register('POST', 'register/submit', 'Register@registerSubmit');
        $this->register('POST', 'login/submit', 'Login@loginSubmit');
        $this->register('GET', 'login/logoutSubmit', 'Login@logoutSubmit');

        $this->register('GET', 'management', 'Management@management');
        $this->register('GET', 'admin/list', 'Admin@list');
        $this->register('GET', 'admin/create', 'Admin@create');
        $this->register('POST', 'admin/createSubmit', 'Admin@createSubmit');
        $this->register('GET', 'admin/edit/{id}', 'Admin@edit');
        $this->register('POST', 'admin/editSubmit/{id}', 'Admin@editSubmit');
        $this->register('POST', 'admin/deleteSubmit/{id}', 'Admin@deleteSubmit');





    }

    private function register(string $method = 'GET', string $url = '/', string $controller = 'Pages'): void
    {
        $this->routes[$method][$url] = $controller;
    }
}
