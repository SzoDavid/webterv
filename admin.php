<?php

session_start();

if (!isset($_SESSION['UserId'])) {
    header('Location: login.php');
    exit();
}

$CURRENT_PAGE = 'admin';

include 'Helpers/header.php';

if (!isset($USER) && !isset($dataSource)) {
    header("Location: error.php");
    exit();
}

if (!$USER->isAdmin()) {
    header('Location: login.php');
    exit();
}

$edit = false;

if (isset($_GET['id'])) {
    $edit = true;
    try {
        $show = $dataSource->createShowDAO()->getById($_GET['id']);
        if (!$show) throw new Exception(404);

        $title = $show->getTitle();
        $episodes = $show->getNumEpisodes();
        $description = $show->getDescription();
        $id = $_GET['id'];
    } catch (Exception $ex) {
        header("Location: error.php?msg=" . $ex->getMessage());
        exit();
    }
}

?>
<main>
    <div>
        <div class="settingsForm">
            <h1><?php echo $edit ? "$title szerkesztése" : 'Sorozat hozzáadása'; ?></h1>
            <form method="POST" action="Helpers/Events/showEvent.php?method=<?php echo $edit ? "update&id=$id" : 'new' ?>" enctype="multipart/form-data">
                <div class="formGrid">
                    <label for="title">Cím</label>
                    <input <?php if ($edit) echo "value=\"$title\""; ?> type="text" id="title" name="title" required>
                    <label for="episodes">Epizódok</label>
                    <input <?php if ($edit) echo "value=\"$episodes\""; ?> type="number" id="episodes" name="episodes" min="0" required>
                    <label for="cover">Borító</label>
                    <input type="file" id="cover" name="cover" accept="image/png, image/jpeg" <?php if (!$edit) echo 'required'; ?>>
                    <label for="trailer">Előzetes</label>
                    <input type="file" id="trailer" name="trailer" accept="video/mp4, video/ogg">
                    <label for="ost">Főcímdal</label>
                    <input type="file" id="ost" name="ost" accept="audio/mpeg, audio/ogg">
                    <label for="description">Leírás</label>
                    <textarea id="description" name="description" cols="100" rows="10"><?php if ($edit) echo $description; ?></textarea>
                </div>
                <?php if ($edit) { ?>
                    <p class="hint">Az üresen hagyott fájl mezők nem lesznek megváltoztatva.</p>
                <?php } ?>
                <div class="oneOneContainer">
                    <div class="left">
                        <input type="submit" value="Mentés">
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