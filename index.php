<?php

session_start();

$CURRENT_PAGE = 'index';

include 'Helpers/header.php';

?>
    <header>
        <img height="250" src="Resources/src/img/logo.svg" alt="logo">
        <h1>BingeVoyage</h1>
    </header>
    <main>
        <div class="oneOneContainer">
            <div class="left">
                <h2>Pár epizód még vár rád!</h2>
                <table class="listTable">
                    <colgroup>
                        <col span="1" style="width: 100px">
                        <col span="2">
                    </colgroup>
                    <tr class="header">
                        <th colspan="2">Cím</th>
                        <th>Megnézendő részek</th>
                    </tr>
                    <tr onclick="window.location.href = 'tmp/sites/shows/mob_psycho_100.html'">
                        <td><img alt="cover" height="100" src="Resources/src/img/mp100.jpg" width="100"></td>
                        <td class="title">Mop Psycho 100</td>
                        <td>18</td>
                    </tr>
                    <tr onclick="window.location.href = 'tmp/sites/shows/cyberpunk_edgerunners.html'">
                        <td><img alt="cover" height="100" src="Resources/src/img/cper.jpg" width="100"></td>
                        <td class="title">Cyberpunk: Edgerunners</td>
                        <td>6</td>
                    </tr>
                </table>
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
                    <tr onclick="window.location.href = 'tmp/sites/shows/hellsing-ultimate.html'">
                        <td><img alt="cover" height="100" src="Resources/src/img/hsu.png" width="100"></td>
                        <td class="title">Hellsing Ultimate</td>
                        <td>4/5</td>
                    </tr>
                </table>
            </div>
        </div>
        <div>
            <h2>Ajánló</h2>
            <div class="equalContainer">
                <div class="recommendationsElement">
                    <h3>A Király</h3>
                    <img src="Resources/src/img/a_kiraly.jpg" alt="cover">
                    <table class="recommendationPropertiesTable">
                        <tr>
                            <th>Epizódok:</th>
                            <th>Értékelés:</th>
                            <th>Nézők:</th>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>3,8/5</td>
                            <td>3</td>
                        </tr>
                    </table>
                    <button onclick="window.location.href='tmp/sites/shows/a_kiraly.html'">Megnézem</button>
                </div>
                <div class="recommendationsElement">
                    <h3>Mob Psycho 100</h3>
                    <img src="Resources/src/img/mp100.jpg" alt="cover">
                    <table class="recommendationPropertiesTable">
                        <tr>
                            <th>Epizódok:</th>
                            <th>Értékelés:</th>
                            <th>Nézők:</th>
                        </tr>
                        <tr>
                            <td>30</td>
                            <td>4,7/5</td>
                            <td>2</td>
                        </tr>
                    </table>
                    <button onclick="window.location.href='tmp/sites/shows/mob_psycho_100.html'">Megnézem</button>
                </div>
                <div class="recommendationsElement">
                    <h3>Hellsing Ultimate</h3>
                    <img src="Resources/src/img/hsu.png" alt="cover">
                    <table class="recommendationPropertiesTable">
                        <tr>
                            <th>Epizódok:</th>
                            <th>Értékelés:</th>
                            <th>Nézők:</th>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>4/5</td>
                            <td>1</td>
                        </tr>
                    </table>
                    <button onclick="window.location.href='tmp/sites/shows/hellsing-ultimate.html'">Megnézem</button>
                </div>
            </div>
        </div>
    </main>
<?php

include 'Helpers/footer.php';

?>