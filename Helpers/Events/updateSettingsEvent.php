<?php

use BL\_enums\EFileCategories;
use BL\_enums\EListVisibility;
use BL\ConfigLoader\ConfigLoader;
use BL\Factories\DataSourceFactory;
use BL\FileManager\FileManager;

require_once '../autoloader.php';

session_start();

function isUploaded(string $field): bool
{
    return file_exists($_FILES[$field]['tmp_name']) && is_uploaded_file($_FILES[$field]['tmp_name']);
}

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();
    $userDAO = $dataSource->createUserDAO();
    $user = $userDAO->getById($_SESSION['UserId']);
    $fileManager = new FileManager($config);
} catch (Exception $ex) {
    header('Location: ../../error.php?msg=' . $ex->getMessage());
    exit();
}


try {
    switch ($_GET['method']) {
        case 'update':

            if (trim($_POST['username'] != '')) $user->setUsername($_POST['username']);
            if (trim($_POST['email'] != '')) $user->setEmail($_POST['email']);

            if ($_POST['visibility'] != $user->getListVisibility()) $user->setListVisibility(
                match ($_POST['visibility']) {
                    "0" => EListVisibility::Private,
                    "1" => EListVisibility::FriendsOnly,
                    "2" => EListVisibility::Public
                });
            if (isUploaded('pfp')) $user->setProfilePicturePath($fileManager->upload($_FILES['pfp'], EFileCategories::Pfp));

            if (!($_POST['password'] == '' && $_POST['passwordAgain'] == '' && $_POST['oldPass'] == '') && ($_POST['password'] == '' || $_POST['passwordAgain'] == '' || $_POST['oldPass'] == '')) {
                $_SESSION['msg'] = 'A jelszó megváltoztatásához mindhárom mezőt ki kell tölteni';
                header('Location: ../../settings.php');
                exit();
            }

            if ($_POST['oldPass'] != '') {

                if (!password_verify($_POST['oldPass'], $user->getPasswordHash())) {
                    $_SESSION['msg'] = 'Hibás régi jelszó';
                    header('Location: ../../settings.php');
                    exit();
                }

                if ($_POST['password'] !== $_POST['passwordAgain']) {
                    $_SESSION['msg'] = 'A jelszavak nem egyeznek meg';
                    header('Location: ../../settings.php');
                    exit();
                }

                if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/", $_POST['password'])) {
                    $_SESSION['msg'] = 'Gyenge jelszó';
                    header('Location: ../../settings.php');
                    exit();
                }

                $user->setPasswordHash(password_hash($_POST['password'], PASSWORD_DEFAULT));
            }
            $userDAO->save($user);
            break;
        case 'remove':
            $userDAO->remove($userDAO->getById($_SESSION['UserId']));
            session_unset();
            session_destroy();
            header("Location: ../../index.php");
            exit();
        default:
        {
            throw new Exception('Unknown method');
        }

    }
} catch (Exception $exception) {
    switch ($exception->getCode()) {
        case 1:
            $_SESSION['msg'] = 'Ez az e-mail cím már foglalt';
            header('Location: ../../settings.php');
            exit();
        case 2:
            $_SESSION['msg'] = 'Ez a felhasználónév már foglalt';
            header('Location: ../../settings.php');
            exit();
        case 10:
            $_SESSION['msg'] = 'Muszáj legalább egy adminnak maradnia';
            header('Location: ../../settings.php');
            exit();
    }
    header('Location: ../../error.php?msg=' . $exception->getMessage());
    exit();
}

header('Location: ../../index.php');
exit();
