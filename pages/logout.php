<?php
/**
 * Deletes the user's token cookie, thus logging them out
 */
require 'data/config.php';
setcookie('token', null, 0, '/');
header('Location: '.SITE_PREFIX);
exit;
?>