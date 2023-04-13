<?php

use BL\ConfigLoader\ConfigLoader;
use BL\Factories\DataSourceFactory;

require_once '../autoloader.php';

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();

    $user = $dataSource->createUserDAO()->getByEmail($_POST['email']);
} catch (Exception $ex) {
    header("Location: ../../error.php?msg=" . $ex->getMessage());
    exit();
}

if ($user == null || !password_verify($_POST['password'], $user->getPasswordHash())) {
    //TODO: return with error feedback
    die('fucked up email or password');
}

$id = $user->getId();

session_start();
$_SESSION['UserId'] = $id;

header('Location: ../../index.php');
