<?php

define('AIRTABLE_ROOT_PATH', dirname(__FILE__));

if (version_compare(PHP_VERSION, '7.1.0', '<')) {
    throw new Exception('The AirTable library requires PHP version 7.1 or higher.');
}

spl_autoload_register(function ($class) {
    $prefix = 'AirTable\\';

    // does the class use the namespace prefix?
    $lenght = strlen($prefix);
    if (strncmp($prefix, $class, $lenght) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relativeClass = substr($class, $lenght);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = rtrim(AIRTABLE_ROOT_PATH, '/') . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});