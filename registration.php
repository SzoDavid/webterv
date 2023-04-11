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
            <h2>Regisztráció</h2>
            <form method="POST" action="Handlers/registrationHandler.php">
                <input name="username" placeholder="Felhasználónév" required type="text"><br>
                <input type="email" name="email" placeholder="E-mail" required><br>
                <input type="password" name="password" placeholder="Jelszó" required><br>
                <input type="password" name="passwordAgain" placeholder="Jelszó mégegyszer" required><br>
                <input type="submit" title="Implementáció a 2. mérföldkőben" value="Regisztráció">
            </form>
            <p>Már van fiókod? <a href="login.php">Jelentkezz be!</a></p>
        </div>
    </main>
<?php

include 'Common/footer.php';

?>
