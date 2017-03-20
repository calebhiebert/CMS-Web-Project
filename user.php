<?php
/**
 * Created by PhpStorm.
 * User: Caleb
 * Date: 3/10/2017
 * Time: 8:16 AM
 */
require_once 'token.php';
require_once 'db/crud.php';
require_once 'PrettyDateTime.php';

use PrettyDateTime\PrettyDateTime;

if($token_valid) {

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $user = getUser($id);

    if($user == null) {
        header('Location: /');
        exit;
    }

} else {
    header('Location: /');
    exit;
}

?>

<?php include 'base.php' ?>
<?php startblock('title') ?>Administration<?php endblock() ?>

<?php startblock('body') ?>
<div class="container mt-3">
    <div class="card">
        <h5 class="card-header"><?= $user->getUsername() ?></h5>
        <ul class="list-group list-group-flush">
            <li class="list-group-item text-muted">User Id: <?= $user->getId() ?></li>
            <li class="list-group-item text-muted">Register Date: <?= PrettyDateTime::parse(date_create_from_format('Y-m-d H:i:s', $user->getRegisterDate()), new DateTime('now')); ?> (<?= $user->getRegisterDate() ?>)</li>
            <li class="list-group-item">Email: <?= $user->getEmail() ?></li>
            <li class="list-group-item">Clearance: <?= $user->getPermLevel() ?></li>
        </ul>
    </div>
</div>
<?php endblock() ?>
