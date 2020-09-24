<?php

declare(strict_types = 1);

namespace App;

use Core\Route;
use Core\Request;
use Core\Router;
use Core\Exception\RouteException;
use Core\Exception\RouterException;
use Exception;

class Application
{
    private $route;
    private $request;
    private $router;

    private const CONTROLLER = "pages";

    public function __construct()
    {
        $this->route = new Route();
        $this->request = new Request();
        $this->router = new Router($this->route, $this->request);
    }

    public function start(): void
    {
        try
        {
            $this->router->match($this->request->getRequestMethod(), $this->request->getUrl());
        }
        catch (RouteException $e)
        {
            http_response_code(400);
            $this->router->dispatch(self::CONTROLLER, "badRequest");
        }
        catch (RouterException $e)
        {
            http_response_code(404);
            $this->router->dispatch(self::CONTROLLER, "notFound");
        }
        catch (Exception $e)
        {
            http_response_code(500);
            $this->router->dispatch(self::CONTROLLER, "serverError");
        }
    }
}
