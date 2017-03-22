<?php
require_once "data/token.php";

$entities = getEntities(ENTITIES_TO_DISPLAY, 0, $token_valid);
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Main Page<?php endblock() ?>

<?php startblock('body') ?>
<div class="container mt-4">
    <div class="card-columns">
        <?php foreach ($entities as $entity): ?>
            <?php $edit = getEntityLastEdit($entity->getId()) ?>
            <div class="card mt-2">
                <h5 class="card-header"><?= $entity->getName() ?></h5>
                <div class="card-block">
                    <p class="card-text">
                        <?= truncate($entity->getDescription(), ENTITY_DESCRIPTION_CHAR_TRUNCATION, '...') ?>
                    </p>
                    <small class="text-muted">Edited <?= prettyTime($edit->getTime()) ?> by <?= $edit->getUsername() ?></small>
                    <br/>
                    <a href="/entity/<?= $entity->getId() ?>" class="card-link">View</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endblock() ?>