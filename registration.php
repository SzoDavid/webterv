<?php

session_start();

if (isset($_SESSION['UserId'])) {
    header('Location: index.php');
    exit();
}

$CURRENT_PAGE = 'registration';

include 'Helpers/header.php';

?>
    <main>
        <div id="loginContainer">
            <h1>BingeVoyage</h1>
            <h2>Regisztráció</h2>
            <form method="POST" action="Helpers/Events/registrationEvent.php">
                <input name="username" placeholder="Felhasználónév" required type="text"><br>
                <input type="email" name="email" placeholder="E-mail" required><br>
                <input type="password" name="password" placeholder="Jelszó" required><br>
                <input type="password" name="passwordAgain" placeholder="Jelszó mégegyszer" required><br>
                <?php if (isset($_SESSION['msg'])) { ?>
                    <p class="hint"><?php echo $_SESSION['msg']?></p>
                <?php unset($_SESSION['msg']); } ?>
                <input type="submit" value="Regisztráció">
            </form>
            <p class="hint">A jelszó legyen legalább 8 karakter hosszú és<br>tartalmazzon kis és nagy angol betűt valamint számot</p>
            <p>Már van fiókod? <a href="login.php">Jelentkezz be!</a></p>
        </div>
    </main>
<?php

include 'Helpers/footer.php';

?>
