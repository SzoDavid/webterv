<?php

use BL\ConfigLoader\ConfigLoader;
use BL\Factories\DataSourceFactory;

require_once '../autoloader.php';

session_start();

if (!(isset($_POST['email']) && !empty(trim($_POST['email'])))
    || !(isset($_POST['password']) && !empty(trim($_POST['password'])))) {
    $_SESSION['msg'] = 'Minden mezőt ki kell tölteni';
    header('Location: ../../registration.php');
    exit();
}

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();

    $user = $dataSource->createUserDAO()->getByEmail($_POST['email']);
} catch (Exception $ex) {
    header("Location: ../../error.php?msg=" . $ex->getMessage());
    exit();
}

if ($user == null || !password_verify($_POST['password'], $user->getPasswordHash())) {
    $_SESSION['msg'] = 'Hibás e-mail vagy jelszó';
    header('Location: ../../login.php');
    exit();
}

$_SESSION['UserId'] = $user->getId();;

header('Location: ../../index.php');
