<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11/10/18
 * Time: 12:34 PM
 */

namespace ReactBridge;


/**
 * @param string $key
 * @param null $default
 * @return array|bool|null|string
 */
function env(string $key, $default = null)
{
    $value = getenv($key);

    if ($value === false) {
        return $default instanceof \Closure ? $default() : $default;
    }

    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }

    if (strlen($value) > 1 && strpos($value, '"') === 0 && strpos($value, '"') === strlen($value) - 1) {
        return substr($value, 1, -1);
    }

    return $value;
}

/**
 * @return bool
 */
function is_prod(): bool
{
    $env = env("APP_ENV", env('REACT_BRIDGE_ENV', 'dev'));

    switch ($env) {
        case 'prod':
        case 'production':
            return true;
        case 'dev':
        case 'development':
        case 'local':
            return false;
    }
}

/**
 * @return bool
 */
function is_dev(): bool
{
    return !is_prod();
}

/**
 * @param string $str
 * @return string
 */
function studly_case(string $str): string
{
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $str)));
}

/**
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function str_has(string $haystack, string $needle): bool
{
    return strpos($haystack, $needle) !== false;
}

/**
 * @param string $id
 * @param string|null $component
 * @param bool $ref
 * @param string $folder
 * @return array
 */
function js_code(string $id, string $component = null, bool $ref = false, string $folder = 'components'): array
{
    $folder = str_replace('/', '', $folder);

    if ($component) {
        $component = str_replace('.', '/', $component);
        $filePath = "./$folder/$component";
        $component = explode('/', $component);
        $component  = $component[count($component) - 1];
        $element = studly_case($id);
    } else {
        $component = studly_case($id);
        $filePath = is_dir("./$folder/$component")
            ? "./$folder/$component/{$component}Container"
            : "./$folder/$component";
        $element = ucfirst($component);
    }

    $element .= 'Element';

    $code = str_replace(
        ["%id%", "%element%", "%component%", "%file_path%", "%ref%"],
        [$id, $element, $component, $filePath, $ref ? str_replace('%component%', $component, REF) : ''],
        ID.'|'.IMPORT.'|'.CODE
    );

    return explode('|', $code);
}

/**
 * @param string $id
 * @param array|null $props
 * @return string
 */
function html(string $id, array $props = null): string
{
    $props = json_encode($props ?: new \stdClass());

    $props = filter_var($props, FILTER_SANITIZE_SPECIAL_CHARS);

    return "<div id='$id' data-props='$props'></div>";
}
