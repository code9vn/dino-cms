<?php

use Dino\Theme\Contracts\Theme as ThemeContract;

if (!function_exists('parse_args')) {
    function parse_args(array|object $args, string|array $defaults = ''): array
    {
        if (is_object($args)) {
            $result = get_object_vars($args);
        } else {
            $result = &$args;
        }

        if (is_array($defaults)) {
            return array_merge($defaults, $result);
        }

        return $result;
    }
}

if (!function_exists('theme')) {
    function theme(string|null $themeName = null, string|null $layoutName = null): mixed
    {
        $theme = app(ThemeContract::class);

        if ($themeName) {
            $theme->theme($themeName);
        }

        if ($layoutName) {
            $theme->layout($layoutName);
        }

        return $theme;
    }
}

if (!function_exists('theme_path')) {
    function theme_path(string|null $path = null): string
    {
        return cms_path('themes' . DIRECTORY_SEPARATOR . $path);
    }
}
