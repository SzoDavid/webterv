<?php

use BL\DTO\_Interfaces\IComment;
use BL\DTO\_Interfaces\IUser;

session_start();

if (!isset($_GET['id'])) {
    header("Location: error.php?msg=404");
    exit();
}

$CURRENT_PAGE = 'show';
require 'Helpers/header.php';

if (!isset($dataSource) || !isset($userDao)) {
    header("Location: error.php");
    exit();
}

$showDao = $dataSource->createShowDAO();
$ratingDao = $dataSource->createRatingDAO();
$commentDao = $dataSource->createCommentDAO();

try {
    $show = $showDao->getById($_GET['id']) ?? throw new Exception('404');
    $numWatching = count($ratingDao->getByShow($show));
    if (isset($USER)) {
        $alsoWatching = $userDao->getFriendsByUserAndShow($USER, $show);
        $status = $ratingDao->getByShowAndUser($show, $USER);
    }
    $comments = $commentDao->getByShow($show);
} catch (Exception $ex) {
    header("Location: error.php?msg=" . $ex->getMessage());
    exit();
}

?>
<script>
    function removeShow() {
        if (confirm('Biztosan törölni akarja a sorozatot?')) {
            window.location.href='Helpers/Events/showEvent.php?method=remove&id=<?php echo $show->getId(); ?>';
        }
    }

    function removeComment(id) {
        if (confirm('Biztosan törölni akarja a hozzászólást?')) {
            window.location.href='Helpers/Events/commentEvent.php?method=remove&id=<?php echo $show->getId(); ?>&comment=' + id;
        }
    }
</script>
<main>
    <div class="oneThreeContainer">
        <div class="left">
            <img class="showCover" src="<?php echo $show->getCoverPath(); ?>" alt="cover">
            <?php
            if (isset($USER)) { ?>
                <button onclick="window.location.href='Helpers/Events/ratingEvent.php?method=<?php echo (($status) ? 'remove' : 'add') . '&id=' . $show->getId(); ?>'"><?php echo ($status) ? 'Levétel listáról' : 'Felvétel listára' ?></button>
            <?php } ?>
            <table class="infoTable">
                <tr>
                    <th colspan="2">Információk</th>
                </tr>
                <tr>
                    <td>Epizódok:</td>
                    <td><?php echo $show->getNumEpisodes(); ?></td>
                </tr>
                <tr>
                    <td>Értékelés:</td>
                    <td><?php try { echo $ratingDao->getAverageRatingByShow($show); } catch (Exception $_) { echo '-'; } ?>/5</td>
                </tr>
                <tr>
                    <td>Nézők:</td>
                    <td><?php echo $numWatching; ?></td>
                </tr>
                <tr>
                    <?php if (isset($USER) && $status) { ?>
                    <td>Szintén nézi:</td>
                    <td>
                        <?php
                        /* @var $friend IUser */
                        foreach ($alsoWatching as $friend) {
                            ?>
                            <a href="user.php?id=<?php echo $friend->getId(); ?>"><?php echo $friend->getUsername(); ?></a><br>
                        <?php } ?>
                    </td>
                    <?php } ?>
                </tr>
            </table>
            <?php if (isset($USER) && $status) { ?>
                <form method="POST" action="Helpers/Events/ratingEvent.php?method=update&id=<?php echo $show->getId(); ?>">
                    <table class="infoTable">
                        <tr>
                            <th colspan="2">Státusz</th>
                        </tr>
                        <tr>
                            <td>Megnézett epizódok:</td>
                            <td><input name="watchedEpisodes" id="watchedEpisodes" type="number" value="<?php echo $status ? $status->getEpisodesWatched() : 0 ?>" min="0" max="10" required></td>
                        </tr>
                        <tr>
                            <td>Értékelés:</td>
                            <td>
                                <select name="rating" id="rating">
                                    <option <?php if ($status->getRating() == null || $status->getRating() == 0) echo 'selected' ?>value="-">-</option>
                                    <option <?php if ($status->getRating() == 1) echo 'selected' ?> value="1">1</option>
                                    <option <?php if ($status->getRating() == 2) echo 'selected' ?> value="2">2</option>
                                    <option <?php if ($status->getRating() == 3) echo 'selected' ?> value="3">3</option>
                                    <option <?php if ($status->getRating() == 4) echo 'selected' ?> value="4">4</option>
                                    <option <?php if ($status->getRating() == 5) echo 'selected' ?> value="5">5</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><button class="saveButton">Mentés</button></td>
                        </tr>
                    </table>
                </form>
                <?php } if (isset($USER) && $USER->isAdmin()) { ?>
                <table class="adminTable">
                    <tr>
                        <th>Moderáció</th>
                    </tr>
                    <tr>
                        <td><button onclick="window.location.href='admin.php?id=<?php echo $show->getId(); ?>'" class="saveButton">Szerkesztés</button></td>
                    </tr>
                    <tr>
                        <td><button onclick="removeShow()" class="saveButton">Eltávolítás</button></td>
                    </tr>
                </table>
            <?php } ?>
        </div>
        <div class="right">
            <h1><?php echo $show->getTitle(); ?></h1>
            <?php if ($show->getDescription()) { ?>
                <div>
                    <h2>Leírás</h2>
                    <p><?php echo $show->getDescription(); ?></p>
                </div>
            <?php } if ($show->getTrailerPath()) { ?>
            <div>
                <h2>Előzetes</h2>
                <video controls>
                    <source src="<?php echo $show->getTrailerPath(); ?>" type="video/<?php echo strtolower(pathinfo($show->getTrailerPath(),PATHINFO_EXTENSION)); ?>">
                </video>
            </div>
            <?php } if ($show->getOstPath()) { ?>
            <div>
                <h2>Főcímdal</h2>
                <audio controls>
                    <source src="<?php echo $show->getOstPath(); ?>" type="audio/<?php echo strtolower(pathinfo($show->getOstPath(),PATHINFO_EXTENSION)); ?>">
                </audio>
            </div>
            <?php } ?>
            <div>
                <h2>Hozzászólások</h2>
                <div>
                    <?php if (isset($USER) && $USER->canComment()) { ?>
                        <div class="newComment">
                            <form method="POST" action="Helpers/Events/commentEvent.php?method=new&id=<?php echo $show->getId(); ?>">
                                <textarea name="comment" rows="4" placeholder="Új hozzászólás..."></textarea>
                                <input type="submit" value="➤">
                            </form>
                        </div>
                    <?php } else if (count($comments) == 0) { ?>
                        <p>Még nincs hozzászólás</p>
                    <?php }
                        /* @var $comment IComment */
                        foreach ($comments as $comment) { ?>
                            <div class="comment">
                                <div class="commentMeta" onclick="window.location.href='user.php?id=<?php echo $comment->getAuthor()->getId(); ?>'">
                                    <?php if ($comment->getAuthor()->getProfilePicturePath()) { ?>
                                        <img src="<?php echo $comment->getAuthor()->getProfilePicturePath(); ?>" width="50" height="50" alt="">
                                    <?php } ?>
                                    <span class="author"><?php echo $comment->getAuthor()->getUsername(); ?></span>
                                    <span class="timestamp"><?php echo $comment->getTime(); ?></span>
                                </div>
                                <p><?php echo $comment->getContent(); ?></p>
                                <?php if (isset($USER) && ($USER->isAdmin() || $USER->getId() == $comment->getAuthor()->getId() )) { ?>
                                    <div class="adminFunctions">
                                        <button onclick="removeComment(<?php echo $comment->getId(); ?>)">Törlés</button>
                                    </div>
                                <?php } ?>
                            </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php

include 'Helpers/footer.php';

?>
