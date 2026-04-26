<?php
namespace App\Core;

/**
 * App Class (Core Router)
 * This class is responsible for:
 * 1. Reading the URL
 * 2. Loading the correct Controller
 * 3. Calling the correct Method
 * 4. Passing parameters to methods
 */
class App
{
    // Default controller if URL is empty
    protected $controller = 'App\\Controllers\\HomeController';

    // Default method
    protected $method = 'index';

    // URL parameters
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

        /* ----------------------------
         * 1. CONTROLLER RESOLUTION
         * ----------------------------
         * Example URL:
         * /item/show/5
         * -> ItemController
         */

        $controllerName = isset($url[0])
        //upercase the first letter and the convert whole thing to lowercase to ensure consistent controller naming (e.g. item -> ItemController)
            ? ucfirst(strtolower($url[0])) . 'Controller'
            : 'HomeController';

        $controllerClass = 'App\\Controllers\\' . $controllerName;
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

        /* ----------------------------
         * 2. METHOD RESOLUTION
         * ----------------------------
         * Example:
         * /item/show/5 -> show()
         */
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        /* ----------------------------
         * 3. PARAMETERS
         * ----------------------------
         * Remaining URL values become parameters
         * Example:
         * /item/show/5 -> [5]
         */
        $this->params = $url ? array_values($url) : [];

        /* ----------------------------
         * 4. EXECUTE CONTROLLER METHOD
         * ----------------------------
         */
        do_action('app.before_dispatch', $this->controller, $this->method, $this->params);

        call_user_func_array(
            [$this->controller, $this->method],
            $this->params
        );

        do_action('app.after_dispatch', $this->controller, $this->method, $this->params);
    }

    /**
     * Parse URL from browser request
     */
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode(
                '/',
                filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)
            );
        }
        return [];
    }
}