<?php

use BL\ConfigLoader\ConfigLoader;
use BL\DTO\Comment;
use BL\Factories\DataSourceFactory;

if (!isset($_GET['method'])) {
    die('`method` must be included in url');
}
if (!isset($_GET['id'])) {
    die('`id` must be included in url');
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
} catch (Exception $ex) {
    //TODO: return with error feedback
    die($ex->getMessage());
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
                die('`comment` must be included in url');
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
    die($exception->getMessage());
}

header('Location: ../../show.php?id=' . $_GET['id']);
exit();
