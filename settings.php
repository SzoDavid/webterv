<?php

use BL\DTO\_Interfaces\IUser as IUser;

session_start();

$CURRENT_PAGE = 'settings';

require 'Helpers/header.php';

if (!isset($dataSource)) {
    //TODO: error page
    die('Oops2');
}

if (!isset($USER)) {
    header('Location: login.php');
    exit();
}


$userDao = $dataSource->createUserDAO();
$showDao = $dataSource->createShowDAO();

?>
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
                        <label for="pfp">Publikus lista</label>
                        <input type="checkbox" id="public" name="public">
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
<?php

include 'Helpers/footer.php';

?>