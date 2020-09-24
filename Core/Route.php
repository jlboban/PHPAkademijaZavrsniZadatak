<?php

declare(strict_types = 1);

namespace Core;

class Route
{
    public $routes = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];

    public function __construct()
    {
        $this->register("GET", "pages", "index", "/");
        $this->register("GET", "pages","events", "pages/events");
        $this->register("GET", "pages", "badRequest", "pages/400");
        $this->register("GET", "pages", "notFound", "pages/404");
        $this->register("GET", "pages", "serverError", "pages/500");

        $this->register("POST", "events", "create", "events/create");

        $this->register("PUT", "events", "edit", "events/edit/{id}");

        $this->register("DELETE", "events", "delete", "events/edit/{id}");
    }

    private function register(string $method, string $controller, string $action, string $url): void
    {
        $this->routes[$method][$controller][$action] = $url;
    }
}
