<?php
namespace App\Core;

class HookManager
{
    private static $actions = [];
    private static $filters = [];

    public static function addAction($hook, callable $callback)
    {
        self::$actions[$hook][] = $callback;
    }

    public static function doAction($hook, ...$args)
    {
        if (empty(self::$actions[$hook])) {
            return;
        }
        foreach (self::$actions[$hook] as $callback) {
            $callback(...$args);
        }
    }

    public static function addFilter($hook, callable $callback)
    {
        self::$filters[$hook][] = $callback;
    }

    public static function applyFilters($hook, $value, ...$args)
    {
        if (empty(self::$filters[$hook])) {
            return $value;
        }
        foreach (self::$filters[$hook] as $callback) {
            $value = $callback($value, ...$args);
        }
        return $value;
    }
}
