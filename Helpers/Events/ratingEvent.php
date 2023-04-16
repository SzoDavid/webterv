<?php

use BL\ConfigLoader\ConfigLoader;
use BL\DTO\Rating;
use BL\Factories\DataSourceFactory;

if (!isset($_GET['method'])) {
    header("Location: ../../error.php?msg=`method` must be included in url");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: ../../error.php?msg=`id` must be included in url");
    exit();
}

session_start();

if (!isset($_SESSION['UserId'])) {
    header('Location: ../../login.php');
    exit();
}

require_once '../autoloader.php';

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();
} catch (Exception $ex) {
    header("Location: ../../error.php?msg=" . $ex->getMessage());
    exit();
}

$ratingDao = $dataSource->createRatingDAO();
$showDao = $dataSource->createShowDAO();
$userDao = $dataSource->createUserDAO();

try {
    switch ($_GET['method']) {
        case 'remove':
            $ratingDao->remove($ratingDao->getByShowAndUser(
                $showDao->getById($_GET['id']),
                $userDao->getById($_SESSION['UserId'])
            ));
            break;
        case 'add':
            $ratingDao->save(Rating::createNewRating(
                $showDao->getById($_GET['id']),
                $userDao->getById($_SESSION['UserId'])
            ));
            break;
        case 'update':
            if (!isset($_POST['rating']) || !isset($_POST['watchedEpisodes'])) {
                header('Location: ../../show.php?id=' . $_GET['id']);
                exit();
            }

            $ratingDao->save($ratingDao->getByShowAndUser(
                $showDao->getById($_GET['id']),
                $userDao->getById($_SESSION['UserId'])
            )->setRating(intval($_POST['rating']))->setEpisodesWatched(intval($_POST['watchedEpisodes'])));
            break;
        default:
            throw new Exception('Unknown method');
    }
} catch (Exception $exception) {
    if ($exception->getCode() != 1) {
        header("Location: ../../error.php?msg=" . $exception->getMessage());
        exit();
    }
}

header('Location: ../../show.php?id=' . $_GET['id']);
exit();
