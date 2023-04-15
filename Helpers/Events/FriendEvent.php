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
    //TODO: return with error feedback
    die($ex->getMessage());
}

try {
    switch ($_GET['method']) {
        case 'add':
            $userDAO->addFriend($userDAO->getById($_SESSION['UserId']),  $userDAO->getById($_GET['id']));
            break;
        case 'remove':
            $userDAO->removeFriend($userDAO->getById($_SESSION['UserId']),  $userDAO->getById($_GET['id']));
            header('Location: ../../index.php');
            exit();
        default:
        {
            throw new Exception('Unknown method');
        }

    }
} catch (Exception $exception) {
    die($exception->getMessage());
}

header('Location: ../../index.php');
exit();
