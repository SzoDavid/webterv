<?php

use BL\ConfigLoader\ConfigLoader;
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
    if (!($user = $userDao->getById($_GET['id']))) {
        header('Location: ../../index.php');
        exit();
    }
    if (!($adminUser = $userDao->getById($_SESSION['UserId'])) || !$adminUser->isAdmin()) {
        header('Location: ../../user.php?id=' . $_GET['id']);
        exit();
    }

    switch ($_GET['method']) {
        case 'mutedSet':
            $userDao->save($user->setCanComment(false));
            break;
        case 'mutedRemove':
            $userDao->save($user->setCanComment(true));
            break;
        case 'adminSet':
            $userDao->save($user->setAdmin(true)->setCanComment(true));
            break;
        case 'adminRemove':
            $userDao->save($user->setAdmin(false));
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

header('Location: ../../user.php?id=' . $_GET['id']);
exit();