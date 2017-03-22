<?php
setcookie('token', null, 0, '/');
header('Location: /');
exit;
?>