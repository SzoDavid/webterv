<?php

use BL\DTO\_Interfaces\IShow;

session_start();

$CURRENT_PAGE = 'shows';

require 'Helpers/header.php';

if (!isset($dataSource)) {
    //TODO: error page
    die('Oops2');
}

$showDao = $dataSource->createShowDAO();
$ratingDao = $dataSource->createRatingDAO();
$userDao = $dataSource->createRatingDAO();

try {
    if (isset($_GET['searchText'])) {
        $shows = $showDao->getBySearchText($_GET['searchText']);
    } else {
        $shows = $showDao->getAll();
    }

} catch (Exception $e) {
    die('Oops');
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

        <?php
        /* @var $shows IShow */
        foreach ($shows as $show) {
            ?>
            <tr onclick="window.location.href = 'show.php?id=<?php echo $show->getId(); ?>'">
                <td><img src="<?php echo $show->getCoverPath(); ?>" alt="cover" width="100" height="100"></td>
                <td class="title"><?php echo $show->getTitle(); ?></td>
                <td><?php echo $show->getNumEpisodes(); ?></td>
                <td><?php echo $ratings = $ratingDao->getAverageRatingByShow($show); ?></td>
                <td><?php echo count($ratings = $ratingDao->getByShow($show)); ?></td>
            </tr>
        <?php } ?>
    </table>
</main>
<?php
    include 'Helpers/footer.php';
?>