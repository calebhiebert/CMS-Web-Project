<?php
setcookie('token', null, 0, '/');
header('Location: '.SITE_PREFIX);
exit;
?>