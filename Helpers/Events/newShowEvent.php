<?php

use BL\_enums\EFileCategories;
use BL\ConfigLoader\ConfigLoader;
use BL\DTO\Show;
use BL\Factories\DataSourceFactory;
use BL\FileManager\FileManager;

require_once '../autoloader.php';

session_start();

if (!isset($_SESSION['UserId'])) {
    header('Location: ../../login.php');
    exit();
}
if(!isset($_POST["submit"])) {
    header('Location: ../../admin.php');
    exit();
}

try {
    $config = new ConfigLoader(__DIR__ . '/../../Resources/config.json');
    $dataSource = (new DataSourceFactory($config))->createDataSource();
    $fileManager = new FileManager($config);

    $user = $dataSource->createUserDAO()->getById($_POST['UserId']);
    if (!$user->isAdmin()) {
        header('Location: ../../index.php');
        exit();
    }

    $show = Show::createNewShow(
        trim($_POST['title']),
        trim($_POST['episodes']),
        trim($_POST['description']) ?? null,
        $fileManager->upload($_FILES['cover'], EFileCategories::Cover),
        (isset($_FILES['trailer'])) ? $fileManager->upload($_FILES['trailer'], EFileCategories::Trailer) : null,
        (isset($_FILES['ost'])) ? $fileManager->upload($_FILES['ost'], EFileCategories::Ost) : null
    );

    $id = $dataSource->createShowDAO()->save($show);

    header("Location: ../../show.php?id=$id");
    exit();
} catch (Exception $ex) {
    //TODO: return with error feedback
    die($ex->getMessage());
}