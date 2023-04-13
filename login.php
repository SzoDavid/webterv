<?php

session_start();

if (isset($_SESSION['UserId'])) {
    header('Location: index.php');
    exit();
}

$CURRENT_PAGE = 'login';

include 'Helpers/header.php';

?>
    <main>
        <div id="loginContainer">
            <h1>BingeVoyage</h1>
            <h2>Bejelentkezés</h2>
            <form method="POST" action="Helpers/Events/loginEvent.php">
                <input type="email" name="email" placeholder="E-mail" required><br>
                <input type="password" name="password" placeholder="Jelszó" required><br>
                <?php if (isset($_SESSION['msg'])) { ?>
                    <p class="hint"><?php echo $_SESSION['msg']?></p>
                <?php unset($_SESSION['msg']); } ?>
                <input type="submit" value="Bejelentkezés">
            </form>
            <p>Még nincs fiókod? <a href="registration.php">Regisztrálj!</a></p>
        </div>
    </main>
<?php

include 'Helpers/footer.php';

?>