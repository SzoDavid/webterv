<?php
session_start();
$CURRENT_PAGE = 'error';
include 'Helpers/header.php';
?>
<main>
    <div class="oneThreeContainer">
        <div class="left">
            <img height="250" src="Resources/src/img/logo.svg" alt="logo">
        </div>
        <div class="right">
            <?php if (isset($_GET['msg']) && $_GET['msg'] == '404') { ?>
                <h1>A keresett oldal nem található!</h1>
            <?php } else { ?>
                <h1>Váratlan hiba történt!</h1>
                <p>Kérjük próbálja újra később!</p>
            <?php } ?>
            <?php if (isset($_GET['msg']) && $_GET['msg'] != '404') { ?>
                <p class="hint"><?php echo $_GET['msg']; ?></p>
            <?php } ?>
        </div>
    </div>
</main>
<?php
include 'Helpers/footer.php';
?>