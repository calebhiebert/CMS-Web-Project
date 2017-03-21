<?php
require_once 'token.php';
require_once 'db/crud.php';

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
            <li class="list-group-item text-muted">Register Date: <?= prettyTime($user->getRegisterDate()) ?> (<?= $user->getRegisterDate() ?>)</li>
            <li class="list-group-item">Email: <?= $user->getEmail() ?></li>
            <li class="list-group-item">Clearance: <?= $user->getPermLevel() ?></li>
        </ul>
    </div>
    <a href="/user/<?= $user->getId() ?>/delete" class="btn btn-danger mt-2">Delete User</a>
</div>
<?php endblock() ?>
