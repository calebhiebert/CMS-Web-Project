<?php
require_once 'data/token.php';
require_once 'data/flickr/phpFlickr.php';

if(!$token_valid) {
    redirect();
    exit;
}

$entityId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$entity = getEntity($entityId);
$images = getEntityImages($entity->getId());

if($entity == null) {
    redirect();
    exit;
}

$f = new phpFlickr(FLICKR_API_KEY);

$picSearch = $f->photos_search(['api_key'=>FLICKR_API_KEY,'text'=>'tiger', 'per_page'=>5]);

?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Add Images<?php endblock() ?>

<?php startblock('style') ?>
<link rel="stylesheet" href="<?= SITE_PREFIX ?>/css/dropzone.css">
<?php endblock() ?>

<?php startblock('body') ?>
    <pre>
        <?php
            var_dump($picSearch);
            var_dump($f->getErrorMsg());
            var_dump($f->getErrorCode());
        ?>
    </pre>
    <div class="container mt-4">
        <div class="card">
            <h5 class="card-header">Upload Picture for <?= $entity->getName() ?></h5>
            <form method="post" enctype="multipart/form-data" class="card-block">
                <fieldset class="form-group">
                    <div id="drop" class="dropzone"></div>
                </fieldset>
                <a href="<?= SITE_PREFIX ?>/entity/<?= urlencode($entity->getName()) ?>" class="btn btn-primary">Done</a>
            </form>
        </div>
    </div>
    <div class="container mt-2">
        <div class="card-columns">
            <?php foreach ($images as $image): ?>
                <div class="card card-inverse">
                    <img class="card-img img-fluid" alt="<?= $image->getName() ?>" src="<?= SITE_PREFIX ?>/images/<?= IMAGE_DISPLAY_SIZE ?>/<?= $image->getId().'.'.$image->getFileExt() ?>">
                    <div class="card-img-overlay">
                        <h5 class="card-title"><?= $image->getName() ?></h5>
                        <p class="card-text"><?= prettyTime(getImageLastEdit($image->getId())['Time']) ?></p>
                        <a class="card-link" href="<?= SITE_PREFIX ?>/image/<?= $image->getId() ?>/delete">Delete</a>
                        <a class="card-link" href="<?= SITE_PREFIX ?>/image/<?= $image->getId() ?>/edit">Edit</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endblock() ?>

<?php startblock('script') ?>
    <script src="<?= SITE_PREFIX ?>/js/dropzone.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        var dzone = new Dropzone("div#drop", {
            url: '<?= SITE_PREFIX ?>/pages/image_processor.php?entityid=<?= $entity->getId() ?>&token=<?= $token ?>',
            paramName: 'image',
            maxFilesize: 10,
            acceptedFiles: '.png,.jpg,.gif,.jpeg',
            addRemoveLinks: true
        });

        dzone.on('queuecomplete', function () {
            location.reload();
        });
    </script>
<?php endblock() ?>
