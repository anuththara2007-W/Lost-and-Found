<?php
namespace App\Core;

class Controller
{
    /**
     * Load a model instance
     */
    public function model($model)
    {
        $modelClass = 'App\\Models\\' . $model;
        require_once ROOT . '/app/Models/' . $model . '.php';
        return new $modelClass();
    }

    /**
     * Load a view and pass data
     */
    public function view($view, $data = [])
    {
        $viewFile = ROOT . '/resources/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            // Extract data array to variables
            extract($data);
            require_once $viewFile;
        } else {
            die("View $view not found.");
        }
    }
}
