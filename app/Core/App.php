<?php
namespace App\Core;
//Finds the controller based on the url
//calling the correct method
class App
{
    // Default controller if URL is empty rederect to HomeController
    protected $controller = 'App\\Controllers\\HomeController';

    // Default method -> if not specified rederect to index
    protected $method = 'index';

    // Stored URL parameters
    protected $params = [];

    /**
     * Constructor runs automatically on every request
     */
    public function __construct()
    {
        // Hook before routing starts (custom event system)
        do_action('app.before_route');

        // Get URL parts
        $url = $this->parseUrl();

        $controllerName = isset($url[0])
        //upercase the first letter and the convert whole thing to lowercase to ensure consistent controller naming
            ? ucfirst(strtolower($url[0])) . 'Controller'
            : 'HomeController';

        //build full class name
        $controllerClass = 'App\\Controllers\\' . $controllerName;
        //build file path for the controller
        $controllerFile = ROOT . '/app/Controllers/' . $controllerName . '.php';

        // Check if controller file exists
        if (file_exists($controllerFile)) {
            $this->controller = $controllerClass;
            unset($url[0]);
        }

        // Load controller file
        require_once ROOT . '/app/Controllers/' .
            str_replace('App\\Controllers\\', '', $this->controller) . '.php';

        // Create controller object
        $this->controller = new $this->controller;
        //check if method exists incontroller
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        //remaining URL values become parameters
        $this->params = $url ? array_values($url) : [];

       //run controller method
        do_action('app.before_dispatch', $this->controller, $this->method, $this->params);

        call_user_func_array(
            [$this->controller, $this->method],
            $this->params
        );

        do_action('app.after_dispatch', $this->controller, $this->method, $this->params);
    }

    //parse url from browser request
    public function parseUrl()
    {
        //check if url exists
        if (isset($_GET['url'])) {
            return explode(
                '/',
                filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)
            );
        }
        return [];
    }
}