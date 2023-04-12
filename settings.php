<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BingeVoyage | Beállítások</title>
    <link rel="stylesheet" href="Resources/src/css/style.css">
    <link rel="icon" type="image/svg" href="Resources/src/img/logo-nobg.svg">
</head>
<body>
<nav>
    <ul class="navbar">
        <li><a href="index.html">Főoldal</a></li>
        <li><a href="shows.html">Sorozatok</a></li>
        <li><a href="people.html">Emberek</a></li>
        <li style="float:right"><a href="profile.html">Próba Ödön</a></li>
        <li style="float:right"><a class="adminOnly" href="admin.html">Felületkezelés</a></li>
    </ul>
</nav>
<main>
    <div>
        <div class="settingsForm">
            <h1>Beállítások</h1>
            <form method="POST">
                <div class="formGrid">
                    <label for="username">Felhasználónév</label>
                    <input type="text" id="username" name="username">
                    <label for="email">E-mail cím</label>
                    <input type="email" id="email" name="email">
                    <label for="pfp">Profilkép</label>
                    <input type="file" id="pfp" name="pfp" accept="image/png, image/jpeg">
                </div>
                <fieldset>
                    <legend>Új jelszó</legend>
                    <div class="formGrid">
                        <label for="pfp">Régi jelszó</label>
                        <input type="password" id="oldPass" name="oldPass">
                        <label for="pfp">Új jelszó</label>
                        <input type="password" id="password" name="password">
                        <label for="pfp">Új jelszó mégegyszer</label>
                        <input type="password" id="passwordAgain" name="passwordAgain">
                    </div>
                </fieldset>
                <p class="hint">Az üresen hagyott mezők nem lesznek megváltoztatva.</p>
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