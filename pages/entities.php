<?php
require_once 'data/token.php';

if(!$token_valid) {
    redirect();
    exit;
} else {
    $edits = [];
    $edits = getEdits(EDITS_TO_DISPLAY, 0);
    $codes = getRegistrationCodes();

    if($current_user == null || $current_user->getPermLevel() != 9) {
        redirect();
        exit;
    } else {
        $users = getUsers();
    }
}
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Administration<?php endblock() ?>

<?php startblock('body') ?>
<link rel="stylesheet" type="text/css" href="<?= SITE_PREFIX ?>/css/theme.default.min.css"/>
<div class="container mt-3">
    <h4>Users</h4>
    <table id="user-table" class="table table-sm table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Email</th>
            <th>Perm Level</th>
            <th>Register Date</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $usr): ?>
            <tr>
                <th scope="row"><?= $usr->getId() ?></th>
                <td><?= $usr->getUsername() ?></td>
                <td><?= $usr->getEmail() ?></td>
                <td><?= CLEARANCE_LEVELS_REV[$usr->getPermLevel()] ?></td>
                <td><?= $usr->getRegisterDate() ?></td>
                <td class="text-center"><a href="<?= SITE_PREFIX ?>/user/<?= $usr->getId() ?>/edit"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endblock() ?>
<?php startblock('script') ?>
<script type="text/javascript" src="<?= SITE_PREFIX ?>/js/jquery.tablesorter.js"></script>
<script>
    $(document).ready(function() {
        $('#user-table').tablesorter();
        $('#edits-table').tablesorter();
    });
</script>
<?php endblock() ?>
