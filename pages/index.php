<?php
require_once "data/token.php";
$entities = getMultiple(
        'SELECT DISTINCT Entities.Id, Name, Description, Parent, Published, EditLog.Time FROM Entities LEFT JOIN EditLog ON Entities.Id = EditLog.EntityId '. ($token_valid ? '' : 'WHERE Published = 1') .' ORDER BY EditLog.Time DESC LIMIT :lmt',
        ['lmt'=>ENTITIES_TO_DISPLAY],
        'Entity'
);

$image = getSingle(
        'SELECT * FROM Pictures ORDER BY rand() LIMIT 1', [],
        'Image'
);

?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Main Page<?php endblock() ?>

<?php startblock('style') ?>
    <link rel="stylesheet" type="text/css" href="<?= SITE_PREFIX ?>/css/bgblur.css"/>
<?php endblock() ?>

<?php startblock('body') ?>
<?php if ($image != null): ?>
    <img id="bg-img" alt="<?= $image->getName() ?>" src="<?= SITE_PREFIX ?>/images/<?= BACKGROUND_IMAGE_SIZE.'/'.$image->getId().'.'.$image->getFileExt() ?>">
    <canvas class="bg" id="bg-img-canvas"></canvas>
<?php endif; ?>
<div class="container mt-4">
    <div class="card-columns">
        <?php foreach ($entities as $entity): ?>
            <?php $edit = getEntityLastEdit($entity->getId()); ?>
            <?php $images = getEntityImages($entity->getId()); ?>
            <div class="card mt-2" >
                <?php if (count($images) > 0): ?>
                    <img class="card-img img-fluid" alt="<?= $images[0]->getName() ?>" src="<?= SITE_PREFIX ?>/images/<?= INDEX_IMAGE_DISPLAY_SIZE?>/<?= $images[0]->getId().'.'.$images[0]->getFileExt() ?>">
                    <div class="pl-4 pt-3 pb-1">
                        <a href="<?= SITE_PREFIX ?>/entity/<?= urlencode($entity->getName()) ?>" style="color: black;"><h5 class="card-title"><?= $entity->getName() ?></h5></a>
                    </div>
                <?php else: ?>
                    <div class="card-block">
                        <a href="<?= SITE_PREFIX ?>/entity/<?= urlencode($entity->getName()) ?>" style="color: black;"><h5 class="card-title"><?= $entity->getName() ?></h5></a>
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

<?php startblock('script') ?>
    <script src="<?= SITE_PREFIX ?>/js/StackBlur.js"></script>
    <script src="<?= SITE_PREFIX ?>/js/bgblur.js"></script>
<?php endblock() ?>
