<?php

class R
{
    private $routes = [];

    /*
    $routes = [
        'GET' => [
            '/users' => callable,
            /users/1 => callable
        ],
        'POST' => [
            '/users' => callable
        ],
    ];
    */

    public function get($route, callable $c)
    {
        $this->add('GET', $route, $c);
    }

    public function post($route, callable $c)
    {
        $this->add('POST', $route, $c);
    }

    public function execute()
    {
        // $key = $_SERVER['PATH_INFO'] ? $_SERVER['PATH_INFO'] : '/';
        // $route = ($this->routes[$key]) ? $this->routes[$key] : $this->routes[''];
        // $route();

        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        foreach ($this->routes[$method] as $pattern => $callback) {

            if (preg_match($pattern, $url, $params)) {
                array_shift($params);
                
                return call_user_func_array(
                    $callback,
                    array_values($params)
                );
            }
        }

        throw new Exception('No route defined for this URI.');
    }

    private function add($method, $route, callable $c)
    {
        // $this->routes[$route] = $callable;
        // $pattern = '/^' . str_replace('/', '\/', $route) . '$/';
        // $this->routes[$pattern] = $callable;

        $pattern = $this->createPattern($route);

        $this->routes[$method][$pattern] = $c;
    }

    private function createPattern($route)
    {
        return '/^' . str_replace('/', '\/', $route) . '$/';
    }
}
