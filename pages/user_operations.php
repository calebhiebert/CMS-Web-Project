<?php
/**
 * Does things to users
 * was supposed to have more stuff but it doesn't because I forgot how to organize big projects
 */
require_once 'data/token.php';

if($token_valid) {

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    switch ($operation) {
        case 'delete':
            deleteUser($id);
            redirect('/admin');
            break;
    }

} else {
    redirect();
    exit;
}
?>
