<?php

$CURRENT_PAGE = 'user';

require 'Common/header.php';

if (!isset($_GET['id'])) {
    //TODO: error page
    die('Oops1');
}

if (!isset($userDao) || !isset($dataSource)) {
    //TODO: error page
    die('Oops2');
}

$showDao = $dataSource->createShowDAO();

try {
    $user = $userDao->getById($_GET['id']) ?? throw new Exception('404');
    $shows = $showDao->getByUser($user);
} catch (Exception $e) {
    die('Oops');
}

$daysSinceReg = round((time() - strtotime($user->getTimestampOfRegistration())) / (60 * 60 * 24));

?>

<main>
    <div class="oneThreeContainer">
        <div class="left">
            <?php if ($user->getProfilePicturePath()) { ?>
                <img class="profilePic" src="<?php $user->getProfilePicturePath() ?>" alt="pfp">
            <?php } ?>
            <button onclick="window.location.href='settings.php'">Beállítások</button>
            <button onclick="window.location.href='logout.php'">Kijelentkezés</button>
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
                    <td><?php echo count($shows) ?></td>
                </tr>
                <tr>
                    <td>Megnézett epizódok:</td>
                    <td>26</td>
                </tr>
                <tr>
                    <td>E-mail (privát):</td>
                    <td>probaodon@betamail.com</td>
                </tr>
                <tr>
                    <td>Barátok:</td>
                    <td><a href="tmp/sites/profiles/szobonya.html">Szobonya</a><br><a href="tmp/sites/profiles/tandi.html">Tandi</a></td>
                </tr>
            </table>
        </div>
        <div class="right">
            <h1>Próba Ödön</h1>
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
                <tr onclick="window.location.href = 'tmp/sites/shows/a_kiraly.html'">
                    <td><img src="Resources/src/img/a_kiraly.jpg" alt="cover" width="100" height="100"></td>
                    <td class="title">A Király</td>
                    <td>10/10</td>
                    <td>5/5</td>
                    <td>3,8/5</td>
                </tr>
                <tr onclick="window.location.href = 'tmp/sites/shows/mob_psycho_100.html'">
                    <td><img src="Resources/src/img/mp100.jpg" alt="cover" width="100" height="100"></td>
                    <td class="title">Mob Psycho 100</td>
                    <td>12/30</td>
                    <td>-/5</td>
                    <td>4,7/5</td>
                </tr>
                <tr onclick="window.location.href = 'tmp/sites/shows/cyberpunk_edgerunners.html'">
                    <td><img src="Resources/src/img/cper.jpg" alt="cover" width="100" height="100"></td>
                    <td class="title">Cyberpunk: Edgerunners</td>
                    <td>4/10</td>
                    <td>-/5</td>
                    <td>4,3/5</td>
                </tr>
            </table>
        </div>
    </div>
</main>

<?php

?>
