<?php

use BL\_enums\EFileCategories;
use BL\ConfigLoader\ConfigLoader;
use BL\DTO\Show;
use BL\Factories\DataSourceFactory;
use BL\FileManager\FileManager;

function isUploaded(string $field): bool {
    return file_exists($_FILES[$field]['tmp_name']) && is_uploaded_file($_FILES[$field]['tmp_name']);
}

if (!isset($_GET['method'])) {
    die('`method` must be included in url');
}

require_once '../autoloader.php';

session_start();

if (!isset($_SESSION['UserId'])) {
    header('Location: ../../login.php');
    exit();
}

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();
    $fileManager = new FileManager($config);

    $user = $dataSource->createUserDAO()->getById($_SESSION['UserId']);
    if (!$user || !$user->isAdmin()) {
        header('Location: ../../index.php');
        exit();
    }
} catch (Exception $ex) {
    //TODO: return with error feedback
    die($ex->getMessage());
}

$showDao = $dataSource->createShowDAO();

try {
    switch ($_GET['method']) {
        case 'new':
            $id = $showDao->save( Show::createNewShow(
                trim($_POST['title']),
                trim($_POST['episodes']),
                trim($_POST['description']) ?? null,
                $fileManager->upload($_FILES['cover'], EFileCategories::Cover),
                (isUploaded('trailer')) ? $fileManager->upload($_FILES['trailer'], EFileCategories::Trailer) : null,
                (isUploaded('ost')) ? $fileManager->upload($_FILES['ost'], EFileCategories::Ost) : null
            ));
            break;
        case 'update':

            break;
        default:
            throw new Exception('Unknown method');
    }
} catch (Exception $exception) {
    die($exception->getMessage());
}

header("Location: ../../show.php?id=$id");
exit();
