<?php

use BL\DTO\_Interfaces\IRating;
use BL\DTO\_Interfaces\IUser;

session_start();

if (!isset($_GET['id'])) {
    //TODO: error page
    die('Oops1');
}


$CURRENT_PAGE = 'user';
require 'Helpers/header.php';

if (!isset($userDao) || !isset($dataSource)) {
    //TODO: error page
    die('Oops2');
}

$showDao = $dataSource->createShowDAO();
$ratingDao = $dataSource->createRatingDAO();

try {
    $user = $userDao->getById($_GET['id']) ?? throw new Exception('404');
    $ratings = $ratingDao->getByUser($user);
    $friends = $userDao->getFriendsByUser($user);
} catch (Exception $e) {
    die('Oops');
}

$daysSinceReg = round((time() - strtotime($user->getTimestampOfRegistration())) / (60 * 60 * 24));

$episodesWatched = 0;
/* @var $rating IRating */
foreach ($ratings as $rating) {
    $episodesWatched += $rating->getEpisodesWatched();
}

?>

<main>
    <div class="oneThreeContainer">
        <div class="left">
            <td><img src="<?php echo $user->getProfilePicturePath() != null ? $user->getProfilePicturePath() : 'Resources/src/img/logo.svg'; ?>" alt="pfp"></td>
            <?php if ($_GET['id'] == $_SESSION['UserId']) { ?>
                <button onclick="window.location.href='settings.php'">Beállítások</button>
                <button onclick="window.location.href='logout.php'">Kijelentkezés</button>
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
                <?php if ($_GET['id'] == $_SESSION['UserId']) { ?>
                <tr>
                    <?php if (!$user->isPublic()) { ?>
                        <td>E-mail (privát):</td>
                        <td><?php echo $user->getEmail() ?></td>
                    <?php }else{ ?>
                        <td>E-mail:</td>
                        <td><?php echo $user->getEmail() ?></td>
                    <?php } ?>
                </tr>
            <?php }else{?>
                <tr>
                    <?php if (!$user->isPublic()) { ?>
                        <td>E-mail:</td>
                        <td><?php echo $user->getEmail() ?></td>
                    <?php }else{ ?>
                        <td>E-mail:</td>
                        <td><em>privát</em></td>
                    <?php } ?>
                </tr>
                <?php }?>
                <tr>
                    <td>Barátok:</td>
                    <td>
                        <?php
                        /* @var $friend IUser */
                        foreach ($friends as $friend) {
                            ?>
                            <a href="user.php?id=<?php echo $friend->getId(); ?>"><?php echo $friend->getUsername(); ?></a>
                            <br>
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <?php if ($_GET['id'] != $_SESSION['UserId'] && isset($USER) && $USER->isAdmin()) { ?>
                <table class="adminTable">
                    <tr>
                        <th>Moderáció</th>
                    </tr>
                    <tr>
                        <td>
                            <button class="saveButton">Bannolás</button>
                        </td>
                    </tr>
                </table>
            <?php } ?>
        </div>
        <div class="right">
            <h1><?php echo $user->getUsername(); ?></h1>
            <h2>Sorozatok</h2>
            <table class="listTable">
                <colgroup>
                    <col span="1" style="width: 100px">
                    <col span="4">
                </colgroup>
                <tr class="header">
                    <th colspan="2">Cím</th>
                    <th>Epizódok</th>
                    <th>Értékelés</th>
                    <th>Átlag</th>
                </tr>
                <?php
                /* @var $rating IRating */
                foreach ($ratings as $rating) {
                    ?>
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
                <?php } ?>
            </table>
        </div>
    </div>
</main>

<?php
include 'Helpers/footer.php';
?>
