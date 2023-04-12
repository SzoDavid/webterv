<?php

use BL\ConfigLoader\ConfigLoader;
use BL\Factories\DataSourceFactory;

spl_autoload_register(function ($class_name) {
    $filename = __DIR__ . '/' . str_replace('\\', '/', $class_name) . '.php';
    include($filename);
});

try {
    $config = new ConfigLoader(__DIR__ . '/Resources/config.json');
    $dataSource =  (new DataSourceFactory($config))->createDataSource();
    $userDAO = $dataSource->createUserDAO();
    $showDAO = $dataSource->createShowDAO();
    $ratingDAO = $dataSource->createRatingDAO();



    print_r($userDAO->getAll());
} catch (Exception $e) {
    echo $e->getMessage();
    return;
}
