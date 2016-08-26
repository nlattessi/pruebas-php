<?php

class R
{
    private $routes = [];

    function add($route, callable $callable)
    {
        $this->routes[$route] = $callable;
    }

    function execute()
    {
        $key = $_SERVER['PATH_INFO'] ? $_SERVER['PATH_INFO'] : '/';

        $route = ($this->routes[$key]) ? $this->routes[$key] : $this->routes[''];

        $route();
    }
}
