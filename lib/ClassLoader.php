<?php

class ClassLoader
{
    public static function autoload($class_name)
    {
        if (class_exists($class_name, false)) {
            return;
        }

        $file_path = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

        $include_paths = explode(PATH_SEPARATOR, get_include_path());
        foreach ($include_paths as $include_path) {
            $full_path = $include_path . DIRECTORY_SEPARATOR . $file_path;

            if (is_readable($full_path)) {
                require_once($full_path);
                break;
            }
        }
    }
}
