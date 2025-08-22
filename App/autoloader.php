<?php
// PSR-4 Light Autoloader for Namespace "App\"
spl_autoload_register(function(string $class){
    $prefix = 'App\\';
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR; // App/ as Root
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative = substr($class, $len);
    $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
    if (is_file($file)) require $file;
});
