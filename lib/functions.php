<?php

function h($string, $flags = ENT_QUOTES, $encoding = 'UTF-8')
{
    return htmlspecialchars($string, $flags, $encoding);
}

function hash_password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function get_uri($uri)
{
    if (defined('BASE_URI_PATH')) {
        $uri = 'http://' . DOMAIN . '/' . ltrim($uri, '/');
    }

    return $uri;
}

function separate_uri(string $uri)
{
    $params = explode('/', strtok($uri, '?'));

    return array_slice($params, 1);
}

function is_natural_number($num, $include_zero = false)
{
    if (is_int($num)) {
        return ($include_zero) ? ($num >= 0) : ($num > 0);
    } elseif (is_string($num)) {
        if ($num === '0' && $include_zero) {
            return true;
        } elseif (preg_match('/^[1-9][0-9]*$/', $num) === 1) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function generate_random_string($length = 16)
{
    $charas   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $char_len = strlen($charas);

    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= $charas[mt_rand(0, $char_len - 1)];
    }

    return $string;
}

function get_db_config()
{
    $config = [];

    $keys = ['HOST', 'NAME', 'USER', 'PASSWORD'];

    foreach ($keys as $key) {
        if (defined('DATABASE_' . $key)) {
            $config[strtolower($key)] = constant('DATABASE_' . $key);
        } else {
            throw new LogicException(__FUNCTION__ . '() DATABASE_' . $key . 'is not defined.');
        }
    }

    return $config;
}

function add_include_path($path, $prepend = false)
{
    $current = get_include_path();

    if ($prepend) {
        set_include_path($path . PATH_SEPARATOR . $current);
    } else {
        set_include_path($current . PATH_SEPARATOR . $path);
    }
}

function is_empty($value)
{
    return ($value === null || $value === '' || $value === []);
}

/**
 * Utility function for debug.
 */
function dump(/* plural args */)
{
    echo '<pre style="background: #fff; color: #333; ' .
        'border: 1px solid #ccc; margin: 5px; padding: 10px;">';

    foreach (func_get_args() as $value) {
        var_dump($value);
    }

    echo '</pre>';
}
