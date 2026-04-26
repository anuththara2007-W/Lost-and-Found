<?php
namespace App\Core;

// Hook system manager class  is used for filters because it allows modifying data dynamically before it is used, 
// making the system flexible and extendable without changing core code.
class HookManager
{
    // Store action callbacks list
    private static $actions = [];

    // Store filter callbacks list
    private static $filters = [];

    // Add action callback to hook
    public static function addAction($hook, callable $callback)
    {
        // Save callback under hook name
        self::$actions[$hook][] = $callback;
    }

    // Run all actions for hook
    public static function doAction($hook, ...$args)
    {
        // Stop if no actions exist
        if (empty(self::$actions[$hook])) {
            return;
        }

        // Execute each action callback
        foreach (self::$actions[$hook] as $callback) {
            $callback(...$args);
        }
    }

    // Add filter callback to hook
    public static function addFilter($hook, callable $callback)
    {
        // Save filter under hook name
        self::$filters[$hook][] = $callback;
    }

    // Apply all filters to value
    public static function applyFilters($hook, $value, ...$args)
    {
        // Return original if no filters
        if (empty(self::$filters[$hook])) {
            return $value;
        }

        // Modify value using filters
        foreach (self::$filters[$hook] as $callback) {
            $value = $callback($value, ...$args);
        }

        // Return final value
        return $value;
    }
}