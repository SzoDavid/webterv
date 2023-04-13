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
                die('`id` must be included in url');
            }
            $id = $_GET['id'];

            $show = $showDao->getById($id)
                ->setTitle($_POST['title'])
                ->setNumEpisodes($_POST['episodes']);

            if (trim($_POST['description'] != '')) $show->setDescription($_POST['description']);
            if (isUploaded('cover')) $show->setCoverPath($fileManager->upload($_FILES['cover'], EFileCategories::Cover));
            if (isUploaded('trailer')) $show->setTrailerPath($fileManager->upload($_FILES['trailer'], EFileCategories::Trailer));
            if (isUploaded('ost')) $show->setOstPath($fileManager->upload($_FILES['ost'], EFileCategories::Ost));

            $showDao->save($show);
            break;
        case 'remove':
            if (!isset($_GET['id'])) {
                die('`id` must be included in url');
            }
            $id = $_GET['id'];

            $showDao->remove($showDao->getById($id));

            header("Location: ../../index.php");
            exit();
        default:
            throw new Exception('Unknown method');
    }
} catch (Exception $exception) {
    die($exception->getMessage());
}

header("Location: ../../show.php?id=$id");
exit();
