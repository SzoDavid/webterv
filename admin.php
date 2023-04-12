<?php

session_start();

if (!isset($_SESSION['UserId'])) {
    header('Location: login.php');
    exit();
}

$CURRENT_PAGE = 'admin';

include 'Helpers/header.php';

if (!isset($USER)) {
    //TODO: error page
    die('Oops');
}

if (!$USER->isAdmin())


?>
<main>
    <div>
        <div class="settingsForm">
            <h1>Sorozat hozzáadása</h1>
            <form method="POST" action="Helpers/Events/newShowEvent.php">
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