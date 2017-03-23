<?php
require_once 'data/token.php';
require_once 'data/ImageResize.php';

use \Eventviva\ImageResize;

if(!$token_valid) {
    header('Location: /');
    exit;
}

const IMAGE_LOCATION = '..'.DIRECTORY_SEPARATOR.'images';

$entityId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$entity = getEntity($entityId);

if($entity == null) {
    header('Location: /');
    exit;
}

if ($_POST) {
    if($_FILES['image']) {
        $file = $_FILES['image'];

        if($file['error'] == 0) {

            $caption = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // generate a random id for this image
            $imageId = random_text('alpha', 16);

            // get the file extension
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // create a new image object to resize the image
            $image = new ImageResize($file['tmp_name']);

            // save to all defined sizes
            foreach (IMAGE_FILE_SIZES as $name => $pxHeight) {
                if(!file_exists(IMAGE_LOCATION.DIRECTORY_SEPARATOR.$name))
                    mkdir(IMAGE_LOCATION.DIRECTORY_SEPARATOR.$name, 0777, true);

                $image->resizeToHeight($pxHeight);
                $image->save(IMAGE_LOCATION . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $imageId . '.' . $ext);
            }

            // add the image to the database
            putImage($imageId, $ext, $entity->getId(), $file['size'], $caption, substr($file['name'], 0, 60));

            header('Location: /entity/'.urlencode($entity->getName()));
            exit;
        } else {
            echo 'Error';
        }
    }
}
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Add Images<?php endblock() ?>

<?php startblock('body') ?>
    <div class="container mt-4">
        <div class="card">
            <h5 class="card-header">Upload Picture for <?= $entity->getName() ?></h5>
            <form method="post" enctype="multipart/form-data" class="card-block">
                <fieldset class="form-group">
                    <label for="caption">Caption</label>
                    <textarea id="caption" name="caption" class="form-control" rows="5"></textarea>
                </fieldset>
                <fieldset class="form-group">
                    <label class="custom-file">
                        <input type="file" name="image" class="custom-file-input">
                        <span class="custom-file-control"></span>
                    </label>
                </fieldset>
                <input class="btn btn-primary" type="submit" name="submit" value="Submit" />
            </form>
        </div>
    </div>
<?php endblock() ?>