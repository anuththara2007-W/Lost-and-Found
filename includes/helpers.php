<?php
/**
 * Application Helpers
 */

if (!function_exists('escape')) {
    /**
     * Escape HTML entities for XSS protection
     *
     * @param string $string
     * @return string
     */
    function escape(string $string): string {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
