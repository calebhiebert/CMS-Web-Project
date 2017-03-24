<?php
require_once 'data/token.php';

if(!$token_valid) {
    header('Location: /');
    exit;
}

$imageId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$entity = filter_input(INPUT_GET, 'entityid', FILTER_VALIDATE_INT);

if(isset($_GET['delete'])) {
    $image = getSingle('SELECT Id, FileExt FROM Pictures WHERE Id = :id', ['id'=>$imageId]);

    deleteImage($imageId);

    //delete the files
    foreach (IMAGE_FILE_SIZES as $NAME => $SIZE) {
        unlink(IMAGE_LOCATION.DIRECTORY_SEPARATOR.$NAME.DIRECTORY_SEPARATOR.$image['Id'].'.'.$image['FileExt']);
    }

    header('Location: '.$_SERVER['HTTP_REFERER']);
    exit;
}

print_r($_GET);

?>