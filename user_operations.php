<?php
require_once 'token.php';
require_once 'db/crud.php';

if($token_valid) {

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    switch ($operation) {
        case 'delete':
            deleteUser($id);
            header('Location: /admin');
            break;
    }

} else {
    header('Location: /');
    exit;
}
?>
