<?php
/**
 * Main page
 */

require_once "token.php";
require_once 'db/crud.php';
require_once "db.php";

$entities = getEntities(9, 0);
?>

<?php include 'base.php' ?>
<?php startblock('title') ?>Main Page<?php endblock() ?>

<?php startblock('body') ?>
<div class="container mt-4">
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