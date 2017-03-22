<?php
require_once 'token.php';

if($token_valid) {

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $user = getUser($id);

    if($user == null) {
        header('Location: /');
        exit;
    }

    if($_POST) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $perm = filter_input(INPUT_POST, 'permlevel', FILTER_VALIDATE_INT);

        if (strlen(trim($username)) < USERNAME_MIN_LENGTH || strlen(trim($username)) > USERNAME_MAX_LENGTH) {
            $msgUname = 'Your username must be between ' . USERNAME_MIN_LENGTH . ' and ' . USERNAME_MAX_LENGTH . ' characters long';
        }

        if (strlen(trim($email)) == 0) {
            $msgEml = 'You must enter an email';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msgEml = 'Please enter a valid email';
        } else if (strlen(trim($email)) > EMAIL_MAX_LENGTH) {
            $msgEml = 'This email is too long! The max length is ' . EMAIL_MAX_LENGTH . 'characters';
        }

        if($perm == null) {
            $msgPerm = 'You must include a permission level';
        } else if(!is_numeric($perm)) {
            $msgPerm = 'The clearance level must be a valid number';
        } else if ($perm < 1 || $perm > 9) { //TODO make into config variables
            $msgPerm = 'The clearance level must be between 1 and 9';
        }
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
    <form action="/user/<?= $user->getId() ?>/edit" method="post">
        <div class="card">
            <h5 class="card-header"><?= $user->getUsername() ?></h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item text-muted">User Id: <?= $user->getId() ?></li>
                <li class="list-group-item text-muted">Register Date: <?= prettyTime($user->getRegisterDate()) ?> (<?= $user->getRegisterDate() ?>)</li>
                <li class="list-group-item form-group<?= isset($msgUname) ? ' has-danger' : '' ?>">
                    <label class="form-control-label" for="username">Username</label>
                    <input id="username" name="username" class="form-control" type="text" value="<?= $_POST ? $username : $user->getUsername() ?>">
                    <div class="form-control-feedback"><?= isset($msgUname) ? $msgUname : '' ?></div>
                </li>
                <li class="list-group-item form-group<?= isset($msgEml) ? ' has-danger' : '' ?>">
                    <label class="form-control-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" value="<?= $_POST ? $email : $user->getEmail() ?>">
                    <div class="form-control-feedback"><?= isset($msgEml) ? $msgEml : '' ?></div>
                </li>
                <li class="list-group-item form-control<?= isset($msgPerm) ? ' has-danger' : '' ?>">
                    <label class="form-control-label" for="perm">Clearance Level</label>
                    <input id="perm" name="permlevel" type="number" class="form-control" value="<?= $_POST ? $perm : $user->getPermLevel() ?>">
                    <div class="form-control-feedback"><?= isset($msgPerm) ? $msgPerm : '' ?></div>
                </li>
            </ul>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Update User</button>
        <a href="/user/<?= $user->getId() ?>/delete" class="btn btn-danger mt-2">Delete User</a>
    </form>
</div>
<?php endblock() ?>
