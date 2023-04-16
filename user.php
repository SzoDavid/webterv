<?php

use BL\DTO\_Interfaces\IRating;
use BL\DTO\_Interfaces\IUser;
use BL\_enums\EListVisibility;

session_start();

if (!isset($_GET['id'])) {
    header("Location: error.php?msg=404");
    exit();
}

$CURRENT_PAGE = 'user';
require 'Helpers/header.php';

if (!isset($userDao) || !isset($dataSource)) {
    header("Location: error.php");
    exit();
}

$showDao = $dataSource->createShowDAO();
$ratingDao = $dataSource->createRatingDAO();

try {
    $user = $userDao->getById($_GET['id']) ?? throw new Exception('404');
    $ratings = $ratingDao->getByUser($user);
    $friends = $userDao->getFriendsByUser($user);
    if (isset($_SESSION['UserId'])) {
        $yourFriends = $userDao->getFriendsByUser($userDao->getById($_SESSION['UserId']));
        $isPrivate = !(($_GET['id'] == $_SESSION['UserId']) || (($user->getListVisibility() === EListVisibility::FriendsOnly) && $isFriend)
            || $user->getListVisibility() === EListVisibility::Public);
    }
    $ratingsNumLim = count($ratings) != 0 && !isPrivate;
    
    
} catch (Exception $ex) {
    header("Location: error.php?msg=" . $ex->getMessage());
    exit();
}

$daysSinceReg = round((time() - strtotime($user->getTimestampOfRegistration())) / (60 * 60 * 24));

$episodesWatched = 0;
/* @var $rating IRating */
foreach ($ratings as $rating) {
    $episodesWatched += $rating->getEpisodesWatched();
}

$isFriend = false;
$isYourFriend = false;

if (isset($yourFriends)) {
    /* @var $yourFriend IUser */
    foreach ($yourFriends as $yourFriend) {
        if ($yourFriend->getId() == $_GET['id']) {
            $isYourFriend = true;
        }
    }
}


if ()

?>
<script>
    function removeUser() {
        if (confirm('Biztosan törölni akarja a felhasználót?')) {
            window.location.href='Helpers/Events/manageUserEvent.php?method=userRemove&id=<?php echo $user->getId(); ?>';
        }
    }
</script>
<main>
    <div class="oneThreeContainer">
        <div class="left">
            <?php if ($user->getProfilePicturePath()) { ?>
                <img class="profilePic" src="<?php echo $user->getProfilePicturePath() ?>" alt="pfp">
            <?php } ?>
            <?php if (isset($_SESSION['UserId']) && $_GET['id'] == $_SESSION['UserId']) { ?>
                <button onclick="window.location.href='settings.php'">Beállítások</button>
                <button onclick="window.location.href='Helpers/Events/logoutEvent.php'">Kijelentkezés</button>
            <?php } ?>
            <table class="infoTable">
                <tr>
                    <th colspan="2">Információk</th>
                </tr>
                <tr>
                    <td>Regisztrált:</td>
                    <td><?php echo $daysSinceReg == 0 ? "Ma" : "$daysSinceReg napja" ?></td>
                </tr>
                <tr>
                    <td>Sorozatok:</td>
                    <td><?php echo count($ratings) ?></td>
                </tr>
                <tr>
                    <td>Megnézett epizódok:</td>
                    <td><?php echo $episodesWatched ?></td>
                </tr>
                <?php if (isset($_SESSION['UserId']) && $_GET['id'] == $_SESSION['UserId']) { ?>
                    <tr>
                        <td>E-mail (privát):</td>
                        <td><?php echo $user->getEmail() ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Barátok:</td>
                    <td>
                        <?php
                        /* @var $friend IUser */
                        foreach ($friends as $friend) {
                            if (isset($_SESSION['UserId']) && $friend->getId() == $_SESSION['UserId']) {
                                $isFriend = true;
                            }
                            ?>
                            <a href="user.php?id=<?php echo $friend->getId(); ?>"><?php echo $friend->getUsername(); ?></a>
                            <br>
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <?php if (isset($_SESSION['UserId']) && $_GET['id'] != $_SESSION['UserId'] && isset($USER)) { ?>
            <form method="POST" action="Helpers/Events/friendEvent.php?method=<?php echo $isYourFriend ? "remove" : "add" ?>&id=<?php echo $_GET['id'] ?> " enctype="multipart/form-data">
                <table class="infoTable">
                    <tr>
                        <td>
                            <button onclick="window.location.href='settings.php'" class="saveButton"><?php echo $isYourFriend ?"Barát eltávolítása" : "Barát hozzáadása"?></button>
                        </td>
                    </tr>
                </table>
            </form>
            <?php } ?>
            <?php if (isset($_SESSION['UserId']) && $_GET['id'] != $_SESSION['UserId'] && isset($USER) && $USER->isAdmin()) { ?>
                <table class="adminTable">
                    <tr>
                        <th>Moderáció</th>
                    </tr>
                    <tr>
                        <td><button onclick="window.location.href='Helpers/Events/manageUserEvent.php?method=admin<?php echo (($user->isAdmin()) ? 'Remove' : 'Set') . '&id=' . $user->getId(); ?>'" class="saveButton">Admin<?php if ($user->isAdmin()) echo ' visszavonása'; ?></button></td>
                    </tr>
                    <?php if (!$user->isAdmin()) {?>
                        <tr>
                            <td><button onclick="window.location.href='Helpers/Events/manageUserEvent.php?method=muted<?php echo (($user->canComment()) ? 'Set' : 'Remove') . '&id=' . $user->getId(); ?>'" class="saveButton">Némítás<?php if (!$user->canComment()) echo ' visszavonása'; ?></button></td>
                        </tr>
                        <tr>
                            <td><button onclick="removeUser()">Profil törlése</button></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
        <div class="right">
            <h1><?php echo $user->getUsername(); ?></h1>
            <h2>Sorozatok</h2>
            <table class="listTable">
                <?php if ($ratingsNumLim) { ?>
                    <colgroup>
                        <col span="1" style="width: 100px">
                        <col span="4">
                    </colgroup>
                <?php } ?>
                <tr class="header">
                    <th <?php if ($ratingsNumLim) echo 'colspan="2"'?>>Cím</th>
                    <th>Epizódok</th>
                    <th>Értékelés</th>
                    <th>Átlag</th>
                </tr>
                <?php if ($ratingsNumLim) {
                    /* @var $rating IRating */
                    foreach ($ratings as $rating) { ?>
                    <tr onclick="window.location.href = 'show.php?id=<?php echo $rating->getShow()->getId(); ?>'">
                        <td><img src="<?php echo $rating->getShow()->getCoverPath(); ?>" alt="cover" width="100"
                                 height="100"></td>
                        <td class="title"><?php echo $rating->getShow()->getTitle(); ?></td>
                        <td><?php echo $rating->getEpisodesWatched() . '/' . $rating->getShow()->getNumEpisodes(); ?></td>
                        <td><?php echo $rating->getRating() ?? '-' ?>/5</td>
                        <td><?php try {
                                echo $ratingDao->getAverageRatingByShow($rating->getShow());
                            } catch (Exception $_) {
                                echo '-';
                            } ?>/5
                        </td>
                    </tr>
                <?php }
                <?php }} else { ?>
                    <tr>
                        <td colspan="4" class="hint"><?php echo $isPrivate ? 'Privát' : 'Üres'?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</main>

<?php
include 'Helpers/footer.php';
?>
