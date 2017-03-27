<?php
require_once 'data/token.php';

$name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$ent = getEntityByName($name);

if($ent != null && ($ent->isPublished() || $token_valid)) {
    $parents = array($ent);
    $inheritedTags = array();
    $created = getEntityCreation($ent->getId());
    $edited = getEntityLastEdit($ent->getId());
    $ent->setTags(getEntityTags($ent->getId()));
    $images = getEntityImages($ent->getId());

    $editString = 'Created ' . prettyTime($created->getTime()) . ($created != $edited ? ' by ' . $created->getUsername() . '. Last edited ' . prettyTime($edited->getTime()) . ' by ' . $edited->getUsername() . '.' : ' by '.$created->getUsername().'.');

    populateParentTree($ent);

    if($token_valid) {
        populateChildren($ent);
    } else {
        populatePublicChildren($ent);
    }

    while ($parents[0]->getParent() != null) {
        array_unshift($parents, $parents[0]->getParent());

        foreach ($parents[0]->getTags() as $tag) {
            array_unshift($inheritedTags, $tag);
        }
    }

    if($ent->getParent() != null) {
        populateChildren($ent->getParent());
    }

} else {
    redirect();
    exit;
}
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Main Page<?php endblock() ?>

<?php startblock('style') ?>
<link rel="stylesheet" type="text/css" href="<?= SITE_PREFIX ?>/css/bgblur.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css"/>
<?php endblock() ?>

<?php startblock('body') ?>
<?php if (count($images) > 0 && SHOW_BACKGROUND_IMAGE): ?>
    <img id="bg-img" alt="<?= $images[0]->getName() ?>" src="<?= SITE_PREFIX ?>/images/<?= BACKGROUND_IMAGE_SIZE.'/'.$images[0]->getId().'.'.$images[0]->getFileExt() ?>">
    <canvas class="bg" id="bg-img-canvas"></canvas>
<?php endif; ?>
<div class="container mt-3 mb-3">
    <?php if (count($parents) > 1): ?>
    <nav class="breadcrumb">
        <?php foreach ($parents as $entity): ?>
            <?php if($entity === end($parents)): ?>
                <span class="breadcrumb-item active"><?= $entity->getName() ?></span>
            <?php else: ?>
                <a class="breadcrumb-item" href="<?= SITE_PREFIX ?><?= '/entity/' . urlencode($entity->getName()) ?>"><?= $entity->getName() ?></a>
            <?php endif; ?>
        <?php endforeach ?>
    </nav>
    <?php endif; ?>
    <?php if(!$ent->isPublished()): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            This page is not visible to the public!
            You are able to view this page because you are logged in.
        </div>
    <?php endif; ?>
    <div class="card">
        <?php if($token_valid): ?>
            <div class="card-header p-2 d-flex justify-content-between">
                <h5 class="mb-0"><?= $ent->getName() ?></h5>
                <div class="p-0">
                    <a class="card-link" href="<?= SITE_PREFIX ?>/entity/<?= $ent->getId() ?>/edit" style="color: black;"><i class="fa fa-pencil"></i></a>
                    <a class="card-link" href="<?= SITE_PREFIX ?>/entity/<?= $ent->getId() ?>/images" style="color: black;"><i class="fa fa-picture-o"></i></a>
                </div>
            </div>
        <?php else: ?>
            <div class="card-header">
                <h5 class="mb-0"><?= $ent->getName() ?></h5>
            </div>
        <?php endif ?>
        <?php if(count($images) > 0 && SHOW_BACKGROUND_IMAGE): ?>
            <div class="card-block slick">
                <?php foreach ($images as $image): ?>
                    <div>
                        <img class="mx-2" alt="<?= $image->getName() ?>" src="<?= SITE_PREFIX ?>/images/<?= IMAGE_DISPLAY_SIZE ?>/<?= $image->getId().'.'.$image->getFileExt() ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="card-block">
            <p class="card-text"><?= nl2br($ent->getDescription()) ?></p>
            <?php if(count($ent->getChildren()) > 0 && DISPLAY_CHILDREN): ?>
                <h6>Children</h6>
                <ul>
                    <?php foreach ($ent->getChildren() as $child): ?>
                        <li><a href="<?= SITE_PREFIX ?>/entity/<?= urlencode($child->getName()) ?>"><?= $child->getName() ?></a></li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
            <?php if($ent->getParent() != null && count($ent->getParent()->getChildren()) - 1 > 0 && DISPLAY_SIBLINGS): ?>
                <h6>Siblings</h6>
                <ul>
                    <?php foreach ($ent->getParent()->getChildren() as $sibling): ?>
                        <?php if($sibling->getId() != $ent->getId() && $sibling->isPublished()): ?>
                            <li><a href="<?= SITE_PREFIX ?>/entity/<?= urlencode($sibling->getName()) ?>"><?= $sibling->getName() ?></a></li>
                        <?php endif ?>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
            <?php foreach ($inheritedTags as $itag): ?>
                <a href="<?= SITE_PREFIX ?>/search?query=<?= urlencode($itag->getTag()) ?>"><span class="badge badge-default mt-1 p-1"><?= $itag->getTag() ?></span></a>
            <?php endforeach; ?>
            <?php foreach ($ent->getTags() as $tag): ?>
                <a href="<?= SITE_PREFIX ?>/search?query=<?= urlencode($tag->getTag()) ?>"><span class="badge badge-primary mt-1 p-1"><?= $tag->getTag() ?></span></a>
            <?php endforeach; ?>
        </div>

        <small class="card-footer text-muted p-2">
            <?= $editString ?>
        </small>
    </div>
</div>
<?php endblock() ?>
<?php startblock('script'); ?>
    <?php if(count($images) > 0 && SHOW_BACKGROUND_IMAGE): ?>
        <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
        <script>
            $('.slick').slick({
                infinite: true,
                dots: true,
                speed: 400,
                variableWidth: true,
                centerMode: true,
                centerPadding: '60px',
                slidesToShow: 1,
                autoplay: true,
                autoplaySpeed: 5000,
                adaptiveHeight: true,
                responsive: [
                    {
                        breakpoint: 576,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            slidesToShow: 1,
                            centerPadding: '30px',
                            infinite: true
                        }
                    }
                ]
            });
        </script>
        <?php if (SHOW_BACKGROUND_IMAGE): ?>
            <script src="<?= SITE_PREFIX ?>/js/StackBlur.js"></script>
            <script src="<?= SITE_PREFIX ?>/js/bgblur.js"></script>
        <?php endif; ?>
    <?php endif; ?>
<?php endblock(); ?>
