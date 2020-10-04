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

        $this->register('GET', 'user/list', 'User@list');

        $this->register('GET', 'admin/list', 'Admin@list');
        $this->register('GET', 'admin/create', 'Admin@create');
        $this->register('GET', 'admin/edit/{id}', 'Admin@edit');
        $this->register('POST', 'admin/createSubmit', 'Admin@createSubmit');
        $this->register('POST', 'admin/editSubmit/{id}', 'Admin@editSubmit');
        $this->register('POST', 'admin/deleteSubmit/{id}', 'Admin@deleteSubmit');

        $this->register('GET', 'venue/list', 'Venue@list');
        $this->register('GET', 'venue/create', 'Venue@create');
        $this->register('GET', 'venue/view', 'Venue@view');
        $this->register('GET', 'venue/view/{id}', 'Venue@view');
        $this->register('GET', 'venue/edit/{id}', 'Venue@edit');
        $this->register('POST', 'venue/createSubmit', 'Venue@createSubmit');
        $this->register('POST', 'venue/editSubmit/{id}', 'Venue@editSubmit');
        $this->register('POST', 'venue/deleteSubmit/{id}', 'Venue@deleteSubmit');

        $this->register('GET', 'musician/list', 'Musician@list');
        $this->register('GET', 'musician/create', 'Musician@create');
        $this->register('GET', 'musician/view', 'Musician@view');
        $this->register('GET', 'musician/view/{id}', 'Musician@view');
        $this->register('GET', 'musician/edit/{id}', 'Musician@edit');
        $this->register('POST', 'musician/createSubmit', 'Musician@createSubmit');
        $this->register('POST', 'musician/editSubmit/{id}', 'Musician@editSubmit');
        $this->register('POST', 'musician/deleteSubmit/{id}', 'Musician@deleteSubmit');
        $this->register('POST','musician/async', 'Musician@asyncMusician');

        $this->register('GET', 'event/list', 'Event@list');
        $this->register('GET', 'event/create', 'Event@create');
        $this->register('GET', 'event/view', 'Event@view');
        $this->register('GET', 'event/view/{id}', 'Event@view');
        $this->register('GET', 'event/edit/{id}', 'Event@edit');
        $this->register('POST', 'event/createSubmit', 'Event@createSubmit');
        $this->register('POST', 'event/editSubmit/{id}', 'Event@editSubmit');
        $this->register('POST', 'event/deleteSubmit/{id}', 'Event@deleteSubmit');
    }

    private function register(string $method = 'GET', string $url = '/', string $controller = 'Pages'): void
    {
        $this->routes[$method][$url] = $controller;
    }
}
