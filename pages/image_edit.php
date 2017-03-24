<?php
require_once 'data/token.php';

if(!$token_valid) {
    header('Location: /');
    exit;
}

$imageId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$entity = filter_input(INPUT_GET, 'entityid', FILTER_VALIDATE_INT);

print_r($_GET);

if(isset($_GET['delete'])) {
    deleteImage($imageId);
    header('Location: '.$_SERVER['HTTP_REFERER']);
    exit;
}
?>