<?php

use BL\DataSource\SQLiteDataSource;
use BL\Rating;

spl_autoload_register(function ($class_name) {
    $filename = __DIR__ . '/' . str_replace('\\', '/', $class_name) . '.php';
    include($filename);
});

try {
    $dataSource = new SQLiteDataSource(realpath('Data/database.db'));
    $user = $dataSource->createUserDAO()->getById(3);
    $show = $dataSource->createShowDAO()->getById(11);

    $rating = Rating::createNewRating($show, $user);
} catch (Exception $e) {
    echo $e->getMessage();
    return;
}
