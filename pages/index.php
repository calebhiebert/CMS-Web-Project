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
            <?php $edit = getEntityLastEdit($entity->getId()); ?>
            <?php $images = getEntityImages($entity->getId()); ?>
            <div class="card mt-2" >
                <?php if (count($images) > 0): ?>
                    <img class="card-img img-fluid" src="/images/<?= INDEX_IMAGE_DISPLAY_SIZE?>/<?= $images[0]->getId().'.'.$images[0]->getFileExt() ?>">
                    <div class="pl-4 pt-3 pb-1">
                        <a href="/entity/<?= urlencode($entity->getName()) ?>" style="color: black;"><h5 class="card-title"><?= $entity->getName() ?></h5></a>
                    </div>
                <?php else: ?>
                    <div class="card-block">
                        <a href="/entity/<?= urlencode($entity->getName()) ?>" style="color: black;"><h5 class="card-title"><?= $entity->getName() ?></h5></a>
                        <p class="card-text">
                            <?= truncate($entity->getDescription(), ENTITY_DESCRIPTION_CHAR_TRUNCATION, '...') ?>
                        </p>
                        <small class="text-muted">Edited <?= prettyTime($edit->getTime()) ?> by <?= $edit->getUsername() ?></small>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endblock() ?>