<?php
/**
 * Created by PhpStorm.
 * User: Caleb
 * Date: 2017-03-16
 * Time: 11:16 AM
 */

setcookie('token', null, time()+60*60*24*30, '/');
header('Location: /');
exit;
?>