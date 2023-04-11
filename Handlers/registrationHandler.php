<?php

use BL\ConfigLoader\ConfigLoader;
use BL\Factories\DataSourceFactory;
use BL\User;

require_once '../Common/autoloader.php';

try {
    $config = new ConfigLoader(__DIR__ . '/../Resources/config.json');
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

header('Location: ../index.php');
