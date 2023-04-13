<?php

use BL\ConfigLoader\ConfigLoader;
use BL\Factories\DataSourceFactory;

if (!isset($CURRENT_PAGE)) {
    $CURRENT_PAGE = 'else';
}

if ($CURRENT_PAGE != 'error') {

    require_once 'autoloader.php';

    try {
        $config = new ConfigLoader(__DIR__ . '/../Resources/config.json');
        $dataSource = (new DataSourceFactory($config))->createDataSource();
        $userDao = $dataSource->createUserDAO();
    } catch (Exception $ex) {
        header("Location: error.php?msg=" . $ex->getMessage());
        exit();
    }

    if (isset($_SESSION['UserId'])) {
        try {
            $USER = $userDao->getById($_SESSION['UserId']);
        } catch (Exception $ex) {
            header("Location: error.php?msg=" . $ex->getMessage());
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BingeVoyage | Főoldal</title>
    <link href="Resources/src/css/style.css" rel="stylesheet">
    <link href="Resources/src/img/logo-nobg.svg" rel="icon" type="image/svg">
</head>
<body>
<nav>
    <ul class="navbar">
        <li><a <?php if ($CURRENT_PAGE == 'index') { ?>class="active"<?php } ?> href="index.php">Főoldal</a></li>
        <li><a <?php if ($CURRENT_PAGE == 'shows') { ?>class="active"<?php } ?> href="shows.php">Sorozatok</a></li>
        <li><a <?php if ($CURRENT_PAGE == 'people') { ?>class="active"<?php } ?> href="people.php">Emberek</a></li>
        <?php if ($CURRENT_PAGE != 'error' && isset($USER)) { ?>
            <li style="float:right"><a <?php if ($CURRENT_PAGE == 'user' && $_GET['id'] == $_SESSION['UserId']) { ?>class="active"<?php } ?> href="user.php?id=<?php echo $_SESSION['UserId'] ?>"><?php echo $USER->getUsername(); ?></a></li>
            <?php if ($USER->isAdmin()) { ?>
                <li style="float:right"><a <?php if ($CURRENT_PAGE == 'admin') { ?>class="active"<?php } ?> class="adminOnly" href="admin.php">Felületkezelés</a></li>
            <?php } ?>
        <?php } else if ($CURRENT_PAGE != 'error') { ?>
            <li style="float:right"><a <?php if ($CURRENT_PAGE == 'login' || $CURRENT_PAGE == 'registration') { ?>class="active"<?php } ?> href="login.php">Bejelentkezés</a></li>
        <?php } ?>
    </ul>
</nav>
