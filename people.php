<?php

use BL\DTO\_Interfaces\IUser as IUser;

function calculateTime(IUser $user) : float {
    return round((time() - strtotime($user->getTimestampOfRegistration())) / (60 * 60 * 24));
}

session_start();

$CURRENT_PAGE = 'people';

require 'Helpers/header.php';

if (!isset($dataSource)) {
    header("Location: error.php");
    exit();
}

$userDao = $dataSource->createUserDAO();
$showDao = $dataSource->createShowDAO();

try {
    if (isset($_GET['searchText'])) {
        $users = $userDao->getBySearchText($_GET['searchText']);
    } else {
        $users = $userDao->getAll();
    }
    $usersNumLim = count($users) != 0;
} catch (Exception $ex) {
    header("Location: error.php?msg=" . $ex->getMessage());
    exit();
}
?>
<main>
    <div class="searchBox">
        <form method="GET">
            <input type="text" name="searchText" value=<?php echo $_GET['searchText'] ?? "" ?>>
            <input type="submit" value="Keresés">
        </form>
    </div>
    <table class="listTable">
        <?php if ($usersNumLim) { ?>
            <colgroup>
                <col span="1" style="width: 100px">
                <col span="3">
            </colgroup>
        <?php } ?>
        <tr class="header">
            <th <?php if ($usersNumLim) echo 'colspan="2"'?>>Név</th>
            <th>Sorozatok</th>
            <th>Regisztrált</th>
        </tr>
        <?php if ($usersNumLim) {
            /* @var $users IUser */
            foreach ($users as $user) {
            ?>
            <tr onclick="window.location.href = 'user.php?id=<?php echo $user->getId(); ?>'">
                <td><img class="scalable" src="<?php echo $user->getProfilePicturePath(); ?>" alt="pfp" width="100" height="100"></td>
                <td class="title"><?php echo $user->getUsername(); ?></td>
                <td><?php try { echo count($showDao->getByUser($user)); } catch (Exception $e) { echo '-'; } ?></td>
                <td><?php echo calculateTime($user) == 0 ? "Ma" : calculateTime($user) . " napja" ?></td>
            </tr>
        <?php }} else { ?>
            <tr>
                <td colspan="4" class="hint">Üres</td>
            </tr>
        <?php } ?>
    </table>
</main>
<?php
    include 'Helpers/footer.php';
?>
