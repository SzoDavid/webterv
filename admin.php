<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BingeVoyage | Felületkezelés</title>
    <link rel="stylesheet" href="Resources/src/css/style.css">
    <link rel="icon" type="image/svg" href="Resources/src/img/logo-nobg.svg">
</head>
<body>
<nav>
    <ul class="navbar">
        <li><a href="index.html">Főoldal</a></li>
        <li><a href="shows.html">Sorozatok</a></li>
        <li><a href="people.html">Emberek</a></li>
        <li style="float:right"><a href="tmp/sites/profiles/profile.html">Próba Ödön</a></li>
        <li style="float:right"><a class="active adminOnly" href="admin.html">Felületkezelés</a></li>
    </ul>
</nav>
<main>
    <div>
        <div class="settingsForm">
            <h1>Sorozat hozzáadása</h1>
            <form method="POST">
                <div class="formGrid">
                    <label for="title">Cím</label>
                    <input type="text" id="title" name="title">
                    <label for="episodes">Epizódok</label>
                    <input type="number" id="episodes" name="episodes" min="0">
                    <label for="cover">Borító</label>
                    <input type="file" id="cover" name="cover" accept="image/png, image/jpeg">
                    <label for="trailer">Előzetes</label>
                    <input type="file" id="trailer" name="trailer" accept="video/mp4, video/ogg">
                    <label for="ost">Főcímdal</label>
                    <input type="file" id="ost" name="ost" accept="audio/mpeg, audio/ogg">
                    <label for="description">Leírás</label>
                    <textarea id="description" name="description" cols="100" rows="10"></textarea>
                </div>
                <div class="oneOneContainer">
                    <div class="left">
                        <input type="submit" title="Implementáció a 2. mérföldkőben" value="Mentés">
                    </div>
                    <div class="right">
                        <input type="reset" value="Visszaállítás">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
</body>
</html>