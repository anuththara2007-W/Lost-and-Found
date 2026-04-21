<?php
namespace App\Core;

class App
{
    protected $controller = 'App\\Controllers\\HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // 1. Controller
        $controllerName = isset($url[0]) ? ucfirst(strtolower($url[0])) . 'Controller' : 'HomeController';
        $controllerClass = 'App\\Controllers\\' . $controllerName;
        $controllerFile = ROOT . '/app/Controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            $this->controller = $controllerClass;
            unset($url[0]);
        }

        require_once ROOT . '/app/Controllers/' . str_replace('App\\Controllers\\', '', $this->controller) . '.php';
        $this->controller = new $this->controller;

        // 2. Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Params
        $this->params = $url ? array_values($url) : [];

        // 4. Call method
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
