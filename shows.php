<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BingeVoyage | Sorozatok</title>
    <link rel="stylesheet" href="Resources/src/css/style.css">
    <link rel="icon" type="image/svg" href="Resources/src/img/logo-nobg.svg">
</head>
<body>
<nav>
    <ul class="navbar">
        <li><a href="index.html">Főoldal</a></li>
        <li><a class="active" href="shows.html">Sorozatok</a></li>
        <li><a href="people.html">Emberek</a></li>
        <li style="float:right"><a href="profile.html">Próba Ödön</a></li>
        <li style="float:right"><a class="adminOnly" href="admin.html">Felületkezelés</a></li>
    </ul>
</nav>
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
        <tr onclick="window.location.href = 'tmp/sites/shows/a_kiraly.html'">
            <td headers="title"><img class="scalable" src="Resources/src/img/a_kiraly.jpg" alt="cover" width="100" height="100"></td>
            <td headers="title" class="title">A Király</td>
            <td headers="episodes">10</td>
            <td headers="rating">3,8/5</td>
            <td headers="watching">3</td>
        </tr>
        <tr onclick="window.location.href = 'tmp/sites/shows/mob_psycho_100.html'">
            <td headers="title"><img class="scalable" src="Resources/src/img/mp100.jpg" alt="cover" width="100" height="100"></td>
            <td headers="title" class="title">Mob Psycho 100</td>
            <td headers="episodes">30</td>
            <td headers="rating">4,7/5</td>
            <td headers="watching">2</td>
        </tr>
        <tr onclick="window.location.href = 'tmp/sites/shows/cyberpunk_edgerunners.html'">
            <td headers="title"><img class="scalable" src="Resources/src/img/cper.jpg" alt="cover" width="100" height="100"></td>
            <td headers="title" class="title">Cyberpunk: Edgerunners</td>
            <td headers="episodes">10</td>
            <td headers="rating">4,3/5</td>
            <td headers="watching">2</td>
        </tr>
        <tr onclick="window.location.href = 'tmp/sites/shows/hellsing-ultimate.html'">
            <td headers="title"><img class="scalable" src="Resources/src/img/hsu.png" alt="cover" width="100" height="100"></td>
            <td headers="title" class="title">Hellsing Ultimate</td>
            <td headers="episodes">10</td>
            <td headers="rating">4/5</td>
            <td headers="watching">1</td>
        </tr>
    </table>
</main>
</body>
</html>