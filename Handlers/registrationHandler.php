<?php

use BL\ConfigLoader\ConfigLoader;
use BL\Factories\DataSourceFactory;
use BL\User;

require_once '../Common/autoloader.php';

try {
    $config = new ConfigLoader();
    $dataSource = (new DataSourceFactory($config))->createDataSource();

    $userDAO = $dataSource->createUserDAO();
    $user = User::createNewUser(
        $_POST['username'],
        password_hash($_POST['password'], PASSWORD_DEFAULT), );

} catch (Exception $ex) {

}


