<?php

use BL\_enums\EFileCategories;
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
} catch (Exception $ex) {
    //TODO: return with error feedback
    die($ex->getMessage());
}

$userDAO = $dataSource->createUserDAO();
try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $user = $userDAO->getById($_SESSION['UserId']);
    $fileManager = new FileManager($config);

} catch (Exception $e) {
}

try {
    switch ($_GET['method']) {
        case 'update':
            if (trim($_POST['username'] != '')) $user->setUsername($_POST['username']);
            if (trim($_POST['email'] != '')) $user->setEmail($_POST['email']);
            if (trim($_POST['public'] != '')) $user->setPublicStatus($_POST['public']);
            if (isUploaded('pfp')) $user->setProfilePicturePath($fileManager->upload($_FILES['pfp'], EFileCategories::Pfp));

            if ($_POST['oldPass'] != '' && password_verify($_POST['oldPass'], $user->getPasswordHash()) && $_POST['password'] == $_POST['passwordAgain']) {

                $user->setPasswordHash(password_hash($_POST['password'], PASSWORD_DEFAULT));
            }

            $userDAO->save($user);
            break;
        case 'remove':

            $userDAO->remove($userDAO->getById($_SESSION['UserId']));
            header("Location: ../../index.php");
            session_unset();
            session_destroy();
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
