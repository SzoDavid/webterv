<?php

use BL\ConfigLoader\ConfigLoader;
use BL\DTO\User;
use BL\Factories\DataSourceFactory;

require_once '../autoloader.php';

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();
} catch (Exception $ex) {
    //TODO: return with error feedback
    die($ex->getMessage());
}

$userDAO = $dataSource->createUserDAO();
$user = User::createNewUser(
    $_POST['username'],
    password_hash($_POST['password'], PASSWORD_DEFAULT),
    $_POST['email']
);

try {
    $id = $userDAO->save($user);
} catch (Exception $ex) {
    //TODO: return with error feedback
    die($ex->getMessage());
}

session_start();
$_SESSION['UserId'] = $id;

header('Location: ../../index.php');
