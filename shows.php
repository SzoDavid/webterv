<?php

use BL\DTO\_Interfaces\IShow;

session_start();

$CURRENT_PAGE = 'shows';

require 'Helpers/header.php';

if (!isset($dataSource)) {
    header("Location: error.php");
    exit();
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
    $showsNumLim = count($shows) != 0;
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
        <?php if ($showsNumLim) { ?>
            <colgroup>
                <col span="1" style="width: 100px">
                <col span="4">
            </colgroup>
        <?php } ?>
        <tr class="header">
            <th id="title" <?php if ($showsNumLim) echo 'colspan="2"'?>>Cím</th>
            <th id="episodes">Epizódok</th>
            <th id="rating">Értékelés</th>
            <th id="watching">Nézők</th>
        </tr>

        <?php if ($showsNumLim) {
            /* @var $shows IShow */
            foreach ($shows as $show) {
            ?>
            <tr onclick="window.location.href = 'show.php?id=<?php echo $show->getId(); ?>'">
                <td headers="title"><img class="scalable" src="<?php echo $show->getCoverPath(); ?>" alt="cover" width="100" height="100"></td>
                <td headers="title" class="title"><?php echo $show->getTitle(); ?></td>
                <td headers="episodes"><?php echo $show->getNumEpisodes(); ?></td>
                <td headers="rating"><?php try { echo $ratingDao->getAverageRatingByShow($show); } catch (Exception $e) { echo '-'; } ?>/5</td>
                <td headers="watching"><?php try { echo count($ratingDao->getByShow($show)); } catch (Exception $e) { echo '-'; } ?></td>
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