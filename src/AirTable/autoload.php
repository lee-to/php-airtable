<?php

define('AIRTABLE_ROOT_PATH', dirname(__FILE__));

if (version_compare(PHP_VERSION, '7.1.0', '<')) {
    throw new Exception('The AirTable library requires PHP version 7.1 or higher.');
}

spl_autoload_register(function ($class) {
    $prefix = 'AirTable\\';

    $length = strlen($prefix);
    if (strncmp($prefix, $class, $length) !== 0) {
        return;
    }

    $relativeClass = substr($class, $length);


    $file = rtrim(AIRTABLE_ROOT_PATH, '/') . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});