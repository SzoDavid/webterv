<?php

use BL\DataSource\SQLiteDataSource;
use BL\Queries\ShowQuery;
use BL\Show;

spl_autoload_register(function ($class_name) {
    $filename = __DIR__ . '/' . str_replace('\\', '/', $class_name) . '.php';
    include($filename);
});

try {
    $db = new SQLiteDataSource(realpath('Data/database.db'));
} catch (Exception $e) {
    echo $e->getMessage();
    return;
}

$show = Show::createNewShow($db, 'A király', 10, 'fasza', null, null, null);

try {
    $show->save();
} catch (Exception $e) {
    echo $e->getMessage();
    return;
}

$showQuery = new ShowQuery($db);

try {
    print_r($showQuery->getAllShows());
} catch (Exception $e) {
    echo $e->getMessage();
    return;
}
