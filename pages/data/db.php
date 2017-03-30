<?php
require 'config.php';

try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=creature;charset=utf8mb4', DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return;
} catch (Exception $exception) {
    $dbErr = $exception->getMessage();
}
?>
<?php if(isset($dbErr)): ?>
    <?php include 'base.php' ?>
    <?php startblock('title') ?>Main Page<?php endblock() ?>

    <?php startblock('body') ?>
        <div class="container mt-4">
            <div class="alert alert-danger">
                <h5>Database Error!</h5>
                <?= $dbErr ?>
                <br />
                Running the db_init script will create any missing tables.
            </div>
        </div>
    <?php endblock() ?>
    <?php exit ?>
<?php endif ?>
