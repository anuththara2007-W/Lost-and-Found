<?php
namespace App\Core;

/**
 * Base Controller Class
 * ---------------------
 * This is the parent class for all controllers in the MVC system.
 * It provides reusable functions:
 * - Loading models
 * - Loading views
 */
class Controller
{
    /**
     * Load a model dynamically
     * ------------------------
     * This function helps controllers access database logic.
     */
    public function model($model)
    {
        // Build full class name (namespace + model name)
        $modelClass = 'App\\Models\\' . $model;

        // Include the model file manually
        require_once ROOT . '/app/Models/' . $model . '.php';

        // Create and return model object
        return new $modelClass();
    }

    /**
     * Load a view (UI file) and pass data to it
     * -----------------------------------------
     * This connects controller → view (presentation layer)
     */
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