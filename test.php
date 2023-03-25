<?php

use BL\DataSource\SQLiteDataSource;

spl_autoload_register(function ($class_name) {
    $filename = __DIR__ . '/' . str_replace('\\', '/', $class_name) . '.php';
    include($filename);
});

try {
    $dataSource = new SQLiteDataSource(realpath('Data/database.db'));
    $userDAO = $dataSource->createUserDAO();
    $showDAO = $dataSource->createShowDAO();
    $ratingDAO = $dataSource->createRatingDAO();

    $user = $userDAO->getById(1);
    $show = $showDAO->getById(11);

    print_r($ratingDAO->getByShow($show));
} catch (Exception $e) {
    echo $e->getMessage();
    return;
}
