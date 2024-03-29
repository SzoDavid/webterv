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
    header("Location: ../../error.php?msg=`method` must be included in url");
    exit();
}

session_start();

if (!isset($_SESSION['UserId'])) {
    header('Location: ../../login.php');
    exit();
}

require_once '../autoloader.php';

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
    header("Location: ../../error.php?msg=" . $ex->getMessage());
    exit();
}

$showDao = $dataSource->createShowDAO();

try {
    switch ($_GET['method']) {
        case 'new':
            $id = $showDao->save(Show::createNewShow(
                trim($_POST['title']),
                trim($_POST['episodes']),
                trim($_POST['description']) ?? null,
                $fileManager->upload($_FILES['cover'], EFileCategories::Cover),
                (isUploaded('trailer')) ? $fileManager->upload($_FILES['trailer'], EFileCategories::Trailer) : null,
                (isUploaded('ost')) ? $fileManager->upload($_FILES['ost'], EFileCategories::Ost) : null
            ));
            break;
        case 'update':
            if (!isset($_GET['id'])) {
                header("Location: ../../error.php?msg=`id` must be included in url");
                exit();
            }
            $id = $_GET['id'];

            $show = $showDao->getById($id)
                ->setTitle($_POST['title'])
                ->setNumEpisodes($_POST['episodes']);

            if (!empty(trim($_POST['description']))) $show->setDescription($_POST['description']);
            if (isUploaded('cover')) $show->setCoverPath($fileManager->upload($_FILES['cover'], EFileCategories::Cover));
            if (isUploaded('trailer')) $show->setTrailerPath($fileManager->upload($_FILES['trailer'], EFileCategories::Trailer));
            if (isUploaded('ost')) $show->setOstPath($fileManager->upload($_FILES['ost'], EFileCategories::Ost));

            $showDao->save($show);
            break;
        case 'remove':
            if (!isset($_GET['id'])) {
                header("Location: ../../error.php?msg=`id` must be included in url");
                exit();
            }
            $id = $_GET['id'];

            $showDao->remove($showDao->getById($id));

            header("Location: ../../index.php");
            exit();
        default:
            throw new Exception('Unknown method');
    }
} catch (Exception $exception) {
    switch ($exception->getCode()) {
        case 1:
            $_SESSION['msg'] = 'A cím nem lehet üres';
            header((isset($_GET['method']) && $_GET['method'] == 'update') ? "Location: ../../admin.php?id=$id" : 'Location: ../../admin.php');
            break;
        case 2:
            $_SESSION['msg'] = 'Az epizódok száma nem lehet negatív';
            header((isset($_GET['method']) && $_GET['method'] == 'update') ? "Location: ../../admin.php?id=$id" : 'Location: ../../admin.php');
            break;
        case 3:
            $_SESSION['msg'] = 'A borító kötelező';
            header('Location: ../../admin.php');
            break;
        default:
            header("Location: ../../error.php?msg=" . $exception->getMessage());
    }
    exit();
}

header("Location: ../../show.php?id=$id");
exit();
