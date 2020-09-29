<?php

declare(strict_types=1);

namespace Core;

class Router
{
    private $request;
    private $route;

    private $defaultController = "pages";
    private $defaultAction = "index";

    private const CONTROLLER_PREFIX = "\\App\\Controller\\";
    private const CONTROLLER_SUFFIX = "Controller";
    private const ACTION_SUFFIX = "Action";

    public function __construct(Request $request, Route $route)
    {
        $this->route = $route;
        $this->request = $request;
    }

    public function match(string $requestMethod, string $url)
    {
        if (array_key_exists($url, $this->route->routes[$requestMethod]))
        {
            $routeParts = explode('@', $this->route->routes[$requestMethod][$url]);
            $controller = $routeParts[0] ?? $this->defaultController; // Pages
            $action = $routeParts[1] ?? $this->defaultAction; // index

            $this->dispatch($controller, $action);
        }
        else
        {
            throw new RouterException("Invalid route.");
        }
    }

    public function dispatch(string $controller, string $action): void
    {
        $controller = self::CONTROLLER_PREFIX . ucfirst($controller) . self::CONTROLLER_SUFFIX;
        $action = $action . self::ACTION_SUFFIX;

        if (!class_exists($controller))
        {
            throw new RouterException("Controller does not exist.");
        }

        $controller = new $controller();

        if (!method_exists($controller, $action))
        {
            throw new RouterException("Action does not exist.");
        }

        $controller->$action();
    }
}
