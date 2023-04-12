<?php

use BL\ConfigLoader\ConfigLoader;
use BL\DTO\Rating;
use BL\Factories\DataSourceFactory;

if (!isset($_GET['method'])) {
    die('`method` must be included in url');
}

require_once '../autoloader.php';

session_start();

if (!isset($_SESSION['UserId'])) {
    header('Location: ../../login.php');
    exit();
}

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();
} catch (Exception $ex) {
    //TODO: return with error feedback
    die($ex->getMessage());
}

$ratingDao = $dataSource->createRatingDAO();
$showDao = $dataSource->createShowDAO();
$userDao = $dataSource->createUserDAO();

if ($_GET['method'] == 'remove') {
    if (!isset($_GET['id'])) {
        die('`id` must be included in url');
    }

    try {
        $ratingDao->remove($ratingDao->getByShowAndUser(
            $showDao->getById($_GET['id']),
            $userDao->getById($_SESSION['UserId'])
        ));
    } catch (Exception $exception) {
        die($exception->getMessage());
    }

    header('Location: ../../show.php?id=' . $_GET['id']);
}

if ($_GET['method'] == 'add') {
    if (!isset($_GET['id'])) {
        die('`id` must be included in url');
    }

    try {
        $ratingDao->save(Rating::createNewRating(
            $showDao->getById($_GET['id']),
            $userDao->getById($_SESSION['UserId'])
        ));
    } catch (Exception $exception) {
        die($exception->getMessage());
    }

    header('Location: ../../show.php?id=' . $_GET['id']);
}

if ($_GET['method'] == 'update') {
    die('Not yet implemented');
}

die('Unknown method');