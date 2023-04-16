<?php

use BL\_enums\EListVisibility;

session_start();

$CURRENT_PAGE = 'settings';

require 'Helpers/header.php';

if (!isset($dataSource)) {
    header("Location: error.php");
    exit();
}

if (!isset($USER)) {
    header('Location: login.php');
    exit();
}


$userDao = $dataSource->createUserDAO();
$showDao = $dataSource->createShowDAO();

try {
    $user = $dataSource->createUserDAO()->getById($_SESSION['UserId']);
    if (!$user) throw new Exception(404);

    $username = $user->getUsername();
    $email = $user->getEmail();
    $listVisibility = $user->getListVisibility();

    $id = $_SESSION['UserId'];
} catch (Exception $e) {
    header('Location: ../../error.php?msg=' . $e->getMessage());
    exit();
}

?>
<script>
    function remove() {
        if (confirm('Biztosan törölni akarja a fiókját?')) {
            window.location.href = 'Helpers/Events/updateSettingsEvent.php?method=remove';
        }
    }
</script>
<main>
    <div>
        <div class="settingsForm">
            <h1>Beállítások</h1>
            <form method="POST" action="Helpers/Events/updateSettingsEvent.php?method=update" enctype="multipart/form-data">
                <div class="formGrid">
                    <label for="username">Felhasználónév</label>
                    <input <?php echo "value=\"$username\""; ?> type="text" id="username" name="username">
                    <label for="email">E-mail cím</label>
                    <input <?php echo "value=\"$email\""; ?> type="email" id="email" name="email">
                    <label for="visibility">Publikus lista</label>
                    <select id="visibility" name="visibility">
                        <option value="0" <?php if ($listVisibility == EListVisibility::Private) echo "selected=selected"; ?>>Privát</option>
                        <option value="1" <?php if ($listVisibility == EListVisibility::FriendsOnly) echo "selected=selected"; ?>>Csak barátoknak</option>
                        <option value="2" <?php if ($listVisibility == EListVisibility::Public) echo "selected=selected"; ?>>Publikus</option>
                    </select>
                        <label for="pfp">Profilkép</label>
                        <input type="file" id="pfp" name="pfp" accept="image/png, image/jpeg">
                </div>
                <p class="hint">Profilkép nem fog megváltozni, ha a mező üres marad.</p>
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
                    <p class="hint"><br>A jelszó legyen legalább 8 karakter hosszú és<br>tartalmazzon kis és nagy angol betűt valamint számot</p>
                </fieldset>
                <p class="hint">
                <?php if (isset($_SESSION['msg'])) { ?>
                    <?php echo $_SESSION['msg']?>
                <?php unset($_SESSION['msg']); }?>
                </p>
                <div class="oneOneContainer">
                    <div class="left">
                        <input type="submit" value="Mentés">
                    </div>
                    <div class="right">
                        <input type="reset" value="Visszaállítás">
                    </div>
                </div>
            </form>
                <div class="adminTable">
                    <button onclick="remove()" class="saveButton">Profil törlése</button>
                </div>
        </div>
    </div>
</main>
<?php

include 'Helpers/footer.php';

?>