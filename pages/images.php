<?php
require_once 'data/token.php';
require_once 'data/ImageResize.php';

use \Eventviva\ImageResize;

if(!$token_valid) {
    header('Location: /');
    exit;
}

$entityId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$entity = getEntity($entityId);

if($entity == null) {
    header('Location: /');
    exit;
}
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Add Images<?php endblock() ?>

<?php startblock('body') ?>
    <link rel="stylesheet" href="/css/dropzone.css">
    <div class="container mt-4">
        <div class="card">
            <h5 class="card-header">Upload Picture for <?= $entity->getName() ?></h5>
            <form method="post" enctype="multipart/form-data" class="card-block">
                <fieldset class="form-group">
                    <div id="drop" class="dropzone"></div>
                </fieldset>
                <a href="/entity/<?= urlencode($entity->getName()) ?>" class="btn btn-primary">Done</a>
            </form>
        </div>
    </div>
<?php endblock() ?>

<?php startblock('script') ?>
    <script src="/js/dropzone.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        var dzone = new Dropzone("div#drop", {
            url: '/pages/image_processor.php?entityid=<?= $entity->getId() ?>&token=<?= $token ?>',
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
