<?php

declare(strict_types = 1);

namespace Core;

use Core\Exception\RouterException;
use Core\Exception\RouteException;

class Router
{
    private $request;
    private $route;

    private $defaultController = "pages";
    private $defaultAction = "index";

    private const CONTROLLER_PREFIX = "\\App\\Controller\\";
    private const CONTROLLER_SUFFIX = "Controller";
    private const ACTION = "Action";

    public function __construct(Route $route, Request $request)
    {
        $this->request = $request;
        $this->route = $route;
    }

    public function match(string $requestMethod, array $url): void
    {
        // Limit 3 parameters
        if (count($url) > 3)
        {
            throw new RouteException("Invalid URL");
        }

        $controller = $url[0] ?? $this->defaultController;
        $action = $url[1] ?? $this->defaultAction;
        $param = $url[2] ?? "";

        // Controller check
        if (!array_key_exists($controller, $this->route->routes[$requestMethod]))
        {
            throw new RouteException("Controller not registered.");
        }

        // Action check
        if (!array_key_exists($action, $this->route->routes[$requestMethod][$controller]))
        {
            throw new RouteException("Action not registered.");
        }

        // Set empty if param isn't an integer
        $param = ctype_digit($param) ? $param : "";

        $this->dispatch($controller, $action, $param);
    }

    public function dispatch(string $controller, string $action, string $param = null): void
    {
        $controller = self::CONTROLLER_PREFIX . ucfirst($controller) . self::CONTROLLER_SUFFIX;
        $action = $action . self::ACTION;

        if (!class_exists($controller))
        {
            throw new RouterException("Controller does not exist.");
        }

        $controller = new $controller();

        if (!method_exists($controller, $action))
        {
            throw new RouterException("Action does not exist.");
        }

        $controller->$action($param);
    }
}
