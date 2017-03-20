<?php
/**
 * Main page
 */

require_once "token.php";
require_once 'db/crud.php';
require_once "db.php";
require_once 'PrettyDateTime.php';

use PrettyDateTime\PrettyDateTime;

$entities = getEntities(9, 0);
?>

<?php include 'base.php' ?>
<?php startblock('title') ?>Main Page<?php endblock() ?>

<?php startblock('body') ?>
<div class="container mt-4">
    <?php if($token_valid): ?>
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <h4 class="card-header"><?= $current_user->getUsername() ?></h4>
                    <div class="card-block">
                        <h5 class="card-title">User Info</h5>
                        <span class="text-muted">Email: <?= $current_user->getEmail() ?></span>
                        <br />
                        <span class="text-muted">Permissions Level: <?= $current_user->getPermLevel() ?></span>
                        <br />
                        <span class="text-muted">Account Creation Date: <?= $current_user->getRegisterDate() ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <h5>All Good!</h5>
                    The database is working properly :D
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <h5>Attention!</h5>
            This website is top secret. If you don't have an account you can kindly scram. If you do please log in <a href="/login">Here</a>
        </div>
    <?php endif ?>
    <div class="card-columns">
        <?php foreach ($entities as $entity): ?>
            <?php if($token_valid || $entity->isPublished()): ?>
                <?php $edit = getEntityLastEdit($entity->getId()) ?>
                <div class="card mt-2">
                    <h5 class="card-header"><?= $entity->getName() ?></h5>
                    <div class="card-block">
                        <p class="card-text">
                            <?= truncate($entity->getDescription(), 100, '...') ?>
                        </p>
                        <small class="text-muted">Edited <?= prettyTime($edit->getTime()) ?> by <?= $edit->getUsername() ?></small>
                        <br/>
                        <a href="/entity/<?= $entity->getId() ?>" class="card-link">View</a>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endblock() ?>