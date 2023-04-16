<?php

use BL\ConfigLoader\ConfigLoader;
use BL\Factories\DataSourceFactory;

require_once '../autoloader.php';

session_start();

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();
    $userDAO = $dataSource->createUserDAO();
    $currentUser = $userDAO->getById($_SESSION['UserId']);
    $user = $userDAO->getById($_GET['id']);
} catch (Exception $ex) {
    header('Location: ../../error.php?msg=' . $ex->getMessage());
    exit();
}

try {
    switch ($_GET['method']) {
        case 'add':
            $userDAO->addFriend($userDAO->getById($_SESSION['UserId']),  $userDAO->getById($_GET['id']));
            break;
        case 'remove':
            $userDAO->removeFriend($userDAO->getById($_SESSION['UserId']),  $userDAO->getById($_GET['id']));
            break;
        default:
        {
            throw new Exception('Unknown method');
        }

    }
} catch (Exception $exception) {
    header('Location: ../../error.php?msg=' . $exception->getMessage());
    exit();
}

header('Location: ../../user.php?id=' . $_GET['id']);
exit();
