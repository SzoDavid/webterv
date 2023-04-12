<?php

use BL\DTO\_Interfaces\IUser as IUser;

function calculateTime(IUser $user) : float {
    return round((time() - strtotime($user->getTimestampOfRegistration())) / (60 * 60 * 24));
}

session_start();

$CURRENT_PAGE = 'people';

require 'Helpers/header.php';

if (!isset($dataSource)) {
    //TODO: error page
    die('Oops2');
}

$userDao = $dataSource->createUserDAO();
$showDao = $dataSource->createShowDAO();

try {
    if (isset($_GET['searchText'])) {
        $users = $userDao->getBySearchText($_GET['searchText']);
    } else {
        $users = $userDao->getAll();
    }

} catch (Exception $e) {
    die('Oops');
}



?>
<main>
    <div class="searchBox">
        <form method="GET">
            <input type="text" name="searchText" value=<?php echo $_GET['searchText'] ?? "" ?>>
            <input type="submit" title="Implementáció a 2. mérföldkőben" value="Keresés">
        </form>
    </div>
    <table class="listTable">
        <colgroup>
            <col span="1" style="width: 100px">
            <col span="3">
        </colgroup>
        <tr class="header">
            <th colspan="2">Név</th>
            <th>Sorozatok</th>
            <th>Regisztrált</th>
        </tr>
        <?php
        /* @var $users IUser */
        foreach ($users as $user) {
            ?>
            <tr onclick="window.location.href = 'profile.php?id=<?php echo $user->getId(); ?>'">
                <td><img src="<?php echo $user->getProfilePicturePath(); ?>" alt="pfp" width="100" height="100"></td>
                <td class="title"><?php echo $user->getUsername(); ?></td>
                <td><?php echo count($shows = $showDao->getByUser($user)); ?></td>
                <td><?php echo  calculateTime($user) == 0 ? "Ma" : calculateTime($user) . " napja" ?></td>
            </tr>
        <?php } ?>
    </table>
</main>
