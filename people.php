<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BingeVoyage | Emberek</title>
    <link rel="stylesheet" href="Resources/src/css/style.css">
    <link rel="icon" type="image/svg" href="Resources/src/img/logo-nobg.svg">
</head>
<body>
<nav>
    <ul class="navbar">
        <li><a href="index.html">Főoldal</a></li>
        <li><a href="shows.html">Sorozatok</a></li>
        <li><a class="active" href="people.html">Emberek</a></li>
        <li style="float:right"><a href="tmp/sites/profiles/profile.html">Próba Ödön</a></li>
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
            <col span="3">
        </colgroup>
        <tr class="header">
            <th colspan="2">Név</th>
            <th>Sorozatok</th>
            <th>Regisztrált</th>
        </tr>
        <tr onclick="window.location.href = 'tmp/sites/profiles/profile.html'">
            <td><img src="Resources/Data/Images/ProfilePictures/pfp.jpg" alt="pfp" width="100" height="100"></td>
            <td class="title">Próba Ödön</td>
            <td>3</td>
            <td>1 hete</td>
        </tr>
        <tr onclick="window.location.href = 'tmp/sites/profiles/szobonya.html'">
            <td><img src="Resources/src/img/pfp2.jpg" alt="pfp" width="100" height="100"></td>
            <td class="title">Szobonya</td>
            <td>2</td>
            <td>2 hete</td>
        </tr>
        <tr onclick="window.location.href = 'tmp/sites/profiles/tandi.html'">
            <td><img src="Resources/src/img/pfp3.jpg" alt="pfp" width="100" height="100"></td>
            <td class="title">Tandi</td>
            <td>3</td>
            <td>5 napja</td>
        </tr>
    </table>
</main>
</body>
</html>