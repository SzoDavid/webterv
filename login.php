<?php

if (isset($_SESSION['UserId'])) {
    header('Location: index.php');
    exit();
}

$CURRENT_PAGE = 'login';

include 'Common/header.php';

?>
    <main>
        <div id="loginContainer">
            <h1>BingeVoyage</h1>
            <h2>Bejelentkezés</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Felhasználónév" required><br>
                <input type="password" name="passwd" placeholder="Jelszó" required><br>
                <input type="submit" title="Implementáció a 2. mérföldkőben" value="Bejelentkezés">
            </form>
            <p>Még nincs fiókod? <a href="registration.php">Regisztrálj!</a></p>
        </div>
    </main>
<?php

include 'Common/footer.php';

?>