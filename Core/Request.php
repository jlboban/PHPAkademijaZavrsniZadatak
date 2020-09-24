<?php

declare(strict_types = 1);

namespace Core;

class Request
{
    private $requestMethod = 'GET';
    private $url = "";

    public function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->url = $this->parseUrl();
    }

    private function parseUrl(): array
    {
        $url = rtrim($_SERVER['REQUEST_URI'] ?? '', '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        array_shift($url);
        array_shift($url); // Removes subfolder from request
        return $url;
    }

    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    public function getUrl(): array
    {
        return $this->url;
    }
}
