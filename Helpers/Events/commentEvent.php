<?php

use BL\ConfigLoader\ConfigLoader;
use BL\DTO\Comment;
use BL\Factories\DataSourceFactory;

if (!isset($_GET['method'])) {
    header("Location: ../../error.php?msg=`method` must be included in url");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: ../../error.php?msg=`id` must be included in url");
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
} catch (Exception $ex) {
    header("Location: ../../error.php?msg=" . $ex->getMessage());
    exit();
}

$commentDao = $dataSource->createCommentDAO();
$showDao = $dataSource->createShowDAO();
$userDao = $dataSource->createUserDAO();

try {
    switch ($_GET['method']) {
        case 'new':
            $commentDao->save(Comment::createNewComment(
                $showDao->getById($_GET['id']),
                $userDao->getById($_SESSION['UserId']),
                $_POST['comment']
            ));
            break;
        case 'remove':
            if (!isset($_GET['comment'])) {
                header("Location: ../../error.php?msg=`comment` must be included in url");
                exit();
            }
            $user = $userDao->getById($_SESSION['UserId']);
            $comment = $commentDao->getById($_GET['comment']);

            if (!$user || (!$user->isAdmin() && $user->getId() != $comment->getAuthor()->getId())) {
                throw new Exception('User has no permission to remove this comment');
            }

            $commentDao->remove($comment);
            break;
        default:
            throw new Exception('Unknown method');
    }
} catch (Exception $exception) {
    if ($exception->getCode() != 1) {
        header("Location: ../../error.php?msg=" . $exception->getMessage());
        exit();
    }
}

header('Location: ../../show.php?id=' . $_GET['id']);
exit();
