<?php

spl_autoload_register(function ($class_name) {
    $filename = __DIR__ . '/../' . str_replace('\\', '/', $class_name) . '.php';
    include($filename);
});
