<?php

use BL\DTO\_Interfaces\IRating;
use BL\DTO\_Interfaces\IShow;

session_start();

$CURRENT_PAGE = 'shows';

require 'Helpers/header.php';

if (!isset($_GET['searchText'])) {
    //TODO: error page
    die('Oops1');
}

if (!isset($dataSource)) {
    //TODO: error page
    die('Oops2');
}

$showDao = $dataSource->createShowDAO();
$ratingDao = $dataSource->createRatingDAO();
$userDao = $dataSource->createRatingDAO();

try {
    $searchedShows = $showDao->getBySearchText($_GET['searchText']);
    $shows = $showDao->getAll();
} catch (Exception $e) {
    die('Oops');
}

?>
<main>
    <div class="searchBox">
        <form method="GET">
            <input type="text" name="searchText">
            <input type="submit" title="Implementáció a 2. mérföldkőben" value="Keresés">
        </form>
    </div>
    <table class="listTable">
        <colgroup>
            <col span="1" style="width: 100px">
            <col span="4">
        </colgroup>
        <tr class="header">
            <th id="title" colspan="2">Cím</th>
            <th id="episodes">Epizódok</th>
            <th id="rating">Értékelés</th>
            <th id="watching">Nézők</th>
        </tr>

        <?php if ($_GET['searchText'] == null) {?>
        <?php
        /* @var $shows IShow */
        foreach ($shows as $show) {
        ?>
        <tr onclick="window.location.href = 'shows.php?id=<?php echo $show->getId(); ?>'">
            <td><img src="<?php echo $show->getCoverPath(); ?>" alt="cover" width="100" height="100"></td>
            <td class="title"><?php echo $show->getTitle(); ?></td>
            <td><?php echo $show->getNumEpisodes(); ?></td>
            <td><?php echo $ratings = $ratingDao->getAverageRatingByShow($show); ?></td>
            <td><?php echo count($ratings = $ratingDao->getByShow($show)); ?></td>
        </tr>
        <?php } } else{
            foreach ($searchedShows as $searchedShow) {
            ?>
            <tr onclick="window.location.href = 'shows.php?id=<?php echo $searchedShow->getId(); ?>'">
                <td><img src="<?php echo $searchedShow->getCoverPath(); ?>" alt="cover" width="100" height="100"></td>
                <td class="title"><?php echo $searchedShow->getTitle(); ?></td>
                <td><?php echo $searchedShow->getNumEpisodes(); ?></td>
                <td><?php echo $ratings = $ratingDao->getAverageRatingByShow($searchedShow); ?></td>
                <td><?php echo count($ratings = $ratingDao->getByShow($searchedShow)); ?></td>
            </tr>
        <?php }} ?>
    </table>
</main>
