<?php

use BL\DTO\_Interfaces\IRating;
use BL\DTO\_Interfaces\IShow;

session_start();

$CURRENT_PAGE = 'index';

include 'Helpers/header.php';

if (!isset($dataSource)) {
    header("Location: error.php");
    exit();
}

$showDao = $dataSource->createShowDAO();
$ratingDao = $dataSource->createRatingDAO();
$userDao = $dataSource->createRatingDAO();

try {
    if (isset($USER)) {
        $toWatchRatings = $ratingDao->getUnwatchedByUser($USER);
        $friendsShows = $showDao->getFriendsShowsByUser($USER);
    }
    $shows = $showDao->getAll();
    $count = count($shows);
    if ($count != 0) {
        $recommendedShows = array_rand($shows, min($count, 3));
        shuffle($recommendedShows);
    }
} catch (Exception $ex) {
    header("Location: error.php?msg=" . $ex->getMessage());
    exit();
}

?>
    <header>
        <img height="250" src="Resources/src/img/logo.svg" alt="logo">
        <h1>BingeVoyage</h1>
    </header>
    <main>
        <?php if (isset($USER)) { ?>
        <div class="oneOneContainer">
            <div class="left">
                <?php if (count($toWatchRatings) == 0) { ?>
                    <h2>Nincs megnézendő epizód a listádon!</h2>
                <?php } else { ?>
                    <h2>Pár epizód még vár rád!</h2>
                    <table class="listTable">
                        <colgroup>
                            <col span="1" style="width: 100px">
                            <col span="3">
                        </colgroup>
                        <tr class="header">
                            <th colspan="2">Cím</th>
                            <th>Következő rész</th>
                            <th>Hátravan</th>
                        </tr>
                        <?php
                            /* @var $toWatchRating IRating */
                            foreach ($toWatchRatings as $toWatchRating) { ?>
                            <tr onclick="window.location.href = 'show.php?id=<?php echo $toWatchRating->getShow()->getId(); ?>'">
                                <td><img alt="cover" height="100" src="<?php echo $toWatchRating->getShow()->getCoverPath(); ?>" width="100"></td>
                                <td class="title"><?php echo $toWatchRating->getShow()->getTitle(); ?></td>
                                <td><?php echo $toWatchRating->getEpisodesWatched() + 1; ?></td>
                                <td><?php echo $toWatchRating->getShow()->getNumEpisodes() - $toWatchRating->getEpisodesWatched(); ?></td>
                            </tr>
                        <?php } ?>
                </table>
                <?php } ?>
            </div>
            <div class="right">
                <h2>Barátaid épp ezt nézik</h2>
                <table class="listTable">
                    <colgroup>
                        <col span="1" style="width: 100px">
                        <col span="2">
                    </colgroup>
                    <tr class="header">
                        <th colspan="2">Cím</th>
                        <th>Értékelés</th>
                    </tr>
                    <?php
                        /* @var $friendsShow IShow */
                        foreach ($friendsShows as $friendsShow) { ?>
                        <tr onclick="window.location.href = 'show.php?id=<?php echo $friendsShow->getId(); ?>'">
                            <td><img alt="cover" height="100" src="<?php echo $friendsShow->getCoverPath(); ?>" width="100"></td>
                            <td class="title"><?php echo $friendsShow->getTitle(); ?></td>
                            <td><?php try { echo $ratingDao->getAverageRatingByShow($friendsShow); } catch (Exception $e) { echo '-'; } ?>/5</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
        <?php } if (isset($recommendedShows)) { ?>
            <div>
                <h2>Ajánló</h2>
                <div class="equalContainer">
                <?php
                    foreach ($recommendedShows as $rsi) { ?>
                        <div class="recommendationsElement">
                            <h3><?php echo $shows[$rsi]->getTitle(); ?></h3>
                            <img src="<?php echo $shows[$rsi]->getCoverPath(); ?>" alt="cover">
                        <table class="recommendationPropertiesTable">
                            <tr>
                                <th>Epizódok:</th>
                                <th>Értékelés:</th>
                                <th>Nézők:</th>
                            </tr>
                            <tr>
                                <td><?php echo $shows[$rsi]->getNumEpisodes(); ?></td>
                                <td><?php try { echo $ratingDao->getAverageRatingByShow($shows[$rsi]); } catch (Exception $e) { echo '-'; } ?>/5</td>
                                <td><?php try { echo count($ratingDao->getByShow($shows[$rsi])); } catch (Exception $e) { echo '-'; } ?></td>
                            </tr>
                        </table>
                        <button onclick="window.location.href='show.php?id=<?php echo $shows[$rsi]->getId(); ?>'">Megnézem</button>
                    </div>
                <?php } ?>
                </div>
            </div>
        <?php } ?>
    </main>
<?php

include 'Helpers/footer.php';

?>