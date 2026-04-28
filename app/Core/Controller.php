<?php
namespace App\Core;
 //abs -hide complex logic from controllers
//runs actoion from user requests
class Controller
{
   //helps controller access database logic
    public function model($model)
    {
        // Build full class name (namespace + model name)
        $modelClass = 'App\\Models\\' . $model;

        // Include the model file manually
        require_once ROOT . '/app/Models/' . $model . '.php';

        // Create and return model object
        return new $modelClass();
    }

    //this function loads a view file and passes data to it
    public function view($view, $data = [])
    {
        // Build full file path for the view
        $viewFile = ROOT . '/resources/views/' . $view . '.php';

        // Check if view exists
        if (file_exists($viewFile)) {

            // Convert array keys into variables
            // Example: $data['title'] → $title
            extract($data);

            // Load the view file
            require_once $viewFile;

        } else {
            // Stop execution if view not found
            die("View $view not found.");
        }
    }
}