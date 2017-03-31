<?php
require_once "data/token.php";
$entities = getMultiple(
        'SELECT DISTINCT Entities.Id, Name, Description, Parent, Published, (SELECT MAX(Time) FROM EditLog WHERE EntityId = Entities.Id) lastEdit FROM Entities '. ($token_valid ? '' : 'WHERE Published = 1') .' ORDER BY lastEdit DESC LIMIT :lmt',
        ['lmt'=>ENTITIES_TO_DISPLAY],
        'Entity'
);
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Main Page<?php endblock() ?>

<?php startblock('style') ?>
    <link rel="stylesheet" type="text/css" href="<?= SITE_PREFIX ?>/css/bgblur.css"/>
<?php endblock() ?>

<?php startblock('body') ?>
<img id="bg-img" class="hidden" alt="background" src="<?= SITE_PREFIX ?>/css/images/mainbg.jpg">
<canvas class="bg" id="bg-img-canvas"></canvas>
<div class="container mt-4">
    <div class="card-columns">
        <?php foreach ($entities as $entity): ?>
            <?php $edit = getEntityLastEdit($entity->getId()); ?>
            <?php $images = getEntityImages($entity->getId()); ?>
            <?php $entUrl = SITE_PREFIX.'/entity/'.urlencode($entity->getName()) ?>
            <div class="card mt-2" >
                <?php if (count($images) > 0): ?>
                    <a href="<?= $entUrl ?>"><img class="card-img img-fluid" alt="<?= $images[0]->getName() ?>" src="<?= SITE_PREFIX ?>/images/<?= INDEX_IMAGE_DISPLAY_SIZE?>/<?= $images[0]->getId().'.'.$images[0]->getFileExt() ?>"></a>
                    <div class="pl-4 pt-3 pb-1">
                        <a href="<?= $entUrl ?>" style="color: black;"><h5 class="card-title"><?= $entity->getName() ?></h5></a>
                    </div>
                <?php else: ?>
                    <div class="card-block">
                        <a href="<?= $entUrl ?>" style="color: black;"><h5 class="card-title"><?= $entity->getName() ?></h5></a>
                        <p class="card-text">
                            <?= truncate(strip_tags(html_entity_decode($entity->getDescription(), ENT_QUOTES, 'UTF-8')), ENTITY_DESCRIPTION_CHAR_TRUNCATION, '...') ?>
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
