<?php

use BL\ConfigLoader\ConfigLoader;
use BL\Factories\DataSourceFactory;

if (!isset($_GET['method'])) {
    die('`method` must be included in url');
}

require_once '../autoloader.php';

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();
} catch (Exception $ex) {
    //TODO: return with error feedback
    die($ex->getMessage());
}

$ratingDao = $dataSource->

if ($_GET['method'] == 'remove') {
    if (!isset($_GET['id'])) {
        die('`id` must be included in url');
    }


}