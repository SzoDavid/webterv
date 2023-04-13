<?php

session_start();

if (!isset($dataSource)) {
    //TODO: error page
    die('Oops2');
}

$userDAO = $dataSource->createUserDAO();
try {
    $currentUser = $userDAO->getById($_SESSION['UserId']);
    $user = $userDAO->getById($_POST['id']);

} catch (Exception $e) {
}

try {
    switch ($_GET['method']) {
        case 'add':
            $userDAO->addFriend($userDAO->getById($_SESSION['UserId']),  $userDAO->getById($_POST['id']));
            break;
        case 'remove':
            $userDAO->removeFriend($userDAO->getById($_SESSION['UserId']),  $userDAO->getById($_POST['id']));
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
