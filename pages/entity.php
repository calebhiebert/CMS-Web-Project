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
    header('Location: /');
    exit;
}
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Main Page<?php endblock() ?>

<?php startblock('body') ?>
<?php if (count($images) > 0 && SHOW_BACKGROUND_IMAGE): ?>
    <style>
        .bg {
            /* Set rules to fill background */
            min-height: 100%;
            min-width: 1920px;

            /* Set up proportionate scaling */
            width: 100%;
            height: auto;

            /* Set up positioning */
            position: fixed;
            top: 0;
            left: 0;

            z-index: -1;
        }

        @media screen and (max-width: 1024px) { /* Specific to this particular image */
            .bg {
                left: 50%;
                margin-left: -512px;   /* 50% */
            }
        }
    </style>
    <img id="bg-img" src="/images/<?= BACKGROUND_IMAGE_SIZE.'/'.$images[0]->getId().'.'.$images[0]->getFileExt() ?>">
    <canvas class="bg" id="bg-img-canvas"></canvas>
<?php endif; ?>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css"/>
<style>
    .shadowed {
        -webkit-box-shadow: 0px 0px 13px 0px rgba(0,0,0,0.40);
        -moz-box-shadow: 0px 0px 13px 0px rgba(0,0,0,0.40);
        box-shadow: 0px 0px 13px 0px rgba(0,0,0,0.40);
    }

    .carousel {
        margin: auto;
    }
</style>
<div class="container mt-3 mb-3">
    <?php if (count($parents) > 1): ?>
    <nav class="breadcrumb">
        <?php foreach ($parents as $entity): ?>
            <?php if($entity === end($parents)): ?>
                <span class="breadcrumb-item active"><?= $entity->getName() ?></span>
            <?php else: ?>
                <a class="breadcrumb-item" href="<?= '/entity/' . urlencode($entity->getName()) ?>"><?= $entity->getName() ?></a>
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
                    <a class="card-link" href="/entity/<?= $ent->getId() ?>/edit" style="color: black;"><i class="fa fa-pencil"></i></a>
                    <a class="card-link" href="/entity/<?= $ent->getId() ?>/images" style="color: black;"><i class="fa fa-picture-o"></i></a>
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
                        <img class="d-block img-fluid" src="/images/<?= IMAGE_DISPLAY_SIZE ?>/<?= $image->getId().'.'.$image->getFileExt() ?>">
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
                        <li><a href="/entity/<?= urlencode($child->getName()) ?>"><?= $child->getName() ?></a></li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
            <?php if($ent->getParent() != null && count($ent->getParent()->getChildren()) - 1 > 0 && DISPLAY_SIBLINGS): ?>
                <h6>Siblings</h6>
                <ul>
                    <?php foreach ($ent->getParent()->getChildren() as $sibling): ?>
                        <?php if($sibling->getId() != $ent->getId() && $sibling->isPublished()): ?>
                            <li><a href="/entity/<?= urlencode($sibling->getName()) ?>"><?= $sibling->getName() ?></a></li>
                        <?php endif ?>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
            <?php foreach ($inheritedTags as $itag): ?>
                <a href="/search?query=<?= urlencode($itag->getTag()) ?>"><span class="badge badge-default mt-1 p-1"><?= $itag->getTag() ?></span></a>
            <?php endforeach; ?>
            <?php foreach ($ent->getTags() as $tag): ?>
                <a href="/search?query=<?= urlencode($tag->getTag()) ?>"><span class="badge badge-primary mt-1 p-1"><?= $tag->getTag() ?></span></a>
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
                            infinite: true
                        }
                    }
                ]
            });
        </script>
        <?php if (SHOW_BACKGROUND_IMAGE): ?>
            <script src="/js/StackBlur.js"></script>
            <script>
                stackBlurImage('bg-img', 'bg-img-canvas', 5, false);
                var rgb = getAverageRGB(document.getElementById('bg-img'));

                $('.navbar')
                    .removeClass('bg-primary')
                    .removeClass('navbar-light')
                    .addClass('navbar-inverse')
                    .addClass('shadowed')
                    .css('background-color', shadeBlend(-0.6, rgbToHex(rgb.r, rgb.g, rgb.b)));

                document.getElementById('bg-img').parentNode.removeChild(document.getElementById('bg-img'));

                function getAverageRGB(imgEl) {

                    var blockSize = 1, // only visit every 5 pixels
                        defaultRGB = {r:0,g:0,b:0}, // for non-supporting envs
                        canvas = document.createElement('canvas'),
                        context = canvas.getContext && canvas.getContext('2d'),
                        data, width, height,
                        i = -4,
                        length,
                        rgb = {r:0,g:0,b:0},
                        count = 0;

                    if (!context) {
                        return defaultRGB;
                    }

                    height = canvas.height = imgEl.naturalHeight || imgEl.offsetHeight || imgEl.height;
                    width = canvas.width = imgEl.naturalWidth || imgEl.offsetWidth || imgEl.width;

                    context.drawImage(imgEl, 0, 0);

                    try {
                        data = context.getImageData(0, 0, width, height);
                    } catch(e) {
                        /* security error, img on diff domain */
                        return defaultRGB;
                    }

                    length = data.data.length;

                    while ( (i += blockSize * 4) < length ) {
                        ++count;
                        rgb.r += data.data[i];
                        rgb.g += data.data[i+1];
                        rgb.b += data.data[i+2];
                    }

                    // ~~ used to floor values
                    rgb.r = ~~(rgb.r/count);
                    rgb.g = ~~(rgb.g/count);
                    rgb.b = ~~(rgb.b/count);

                    return rgb;

                }

                function componentToHex(c) {
                    var hex = c.toString(16);
                    return hex.length == 1 ? "0" + hex : hex;
                }

                function rgbToHex(r, g, b) {
                    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
                }

                function shadeBlend(p,c0,c1) {
                    var n=p<0?p*-1:p,u=Math.round,w=parseInt;
                    if(c0.length>7){
                        var f=c0.split(","),t=(c1?c1:p<0?"rgb(0,0,0)":"rgb(255,255,255)").split(","),R=w(f[0].slice(4)),G=w(f[1]),B=w(f[2]);
                        return "rgb("+(u((w(t[0].slice(4))-R)*n)+R)+","+(u((w(t[1])-G)*n)+G)+","+(u((w(t[2])-B)*n)+B)+")"
                    }else{
                        var f=w(c0.slice(1),16),t=w((c1?c1:p<0?"#000000":"#FFFFFF").slice(1),16),R1=f>>16,G1=f>>8&0x00FF,B1=f&0x0000FF;
                        return "#"+(0x1000000+(u(((t>>16)-R1)*n)+R1)*0x10000+(u(((t>>8&0x00FF)-G1)*n)+G1)*0x100+(u(((t&0x0000FF)-B1)*n)+B1)).toString(16).slice(1)
                    }
                }
            </script>
        <?php endif; ?>
    <?php endif; ?>
<?php endblock(); ?>
