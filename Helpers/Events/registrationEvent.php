<?php

use BL\ConfigLoader\ConfigLoader;
use BL\DTO\User;
use BL\Factories\DataSourceFactory;

require_once '../autoloader.php';

session_start();

if (!(isset($_POST['username']) && !empty(trim($_POST['username'])))
    || !(isset($_POST['email']) && !empty(trim($_POST['email'])))
    || !(isset($_POST['password']) && !empty(trim($_POST['password'])))
    || !(isset($_POST['passwordAgain']) && !empty(trim($_POST['passwordAgain'])))) {
    $_SESSION['msg'] = 'Minden mezőt ki kell tölteni';
    header('Location: ../../registration.php');
    exit();
}

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();
} catch (Exception $ex) {
    header('Location: ../../error.php?msg=' . $ex->getMessage());
    exit();
}

if ($_POST['password'] !== $_POST['passwordAgain']) {
    $_SESSION['msg'] = 'A jelszavak nem egyeznek meg';
    header('Location: ../../registration.php');
    exit();
}
if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/", $_POST['password'])) {
    $_SESSION['msg'] = 'Gyenge jelszó';
    header('Location: ../../registration.php');
    exit();
}

$userDAO = $dataSource->createUserDAO();

try {
    $id = $userDAO->save(User::createNewUser(
        $_POST['username'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['email']
    ));
} catch (Exception $ex) {
    switch ($ex->getCode()) {
        case 1:
            $_SESSION['msg'] = 'Az e-mailhez már tartozik fiók';
            break;
        case 2:
            $_SESSION['msg'] = 'A felhasználónév foglalt';
            break;
        case 3:
            $_SESSION['msg'] = 'Invalid e-mail cím';
            break;
        default:
            header('Location: ../../error.php?msg=' . $ex->getMessage());
            exit();
    }

    header('Location: ../../registration.php');
    exit();
}

$_SESSION['UserId'] = $id;

header('Location: ../../index.php');
