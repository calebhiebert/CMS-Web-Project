<?php
require_once '../vendor/autoload.php';
require_once 'token.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= SITE_PREFIX ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= SITE_PREFIX ?>/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php startblock('title') ?><?php endblock()?></title>
    <?php startblock('style') ?>
    <?php endblock() ?>
</head>
<body>
<nav class="navbar navbar-toggleable-md bg-primary navbar-light p-1 pl-2">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navContent">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="<?= SITE_PREFIX ?>/">O.P.E Creature Database</a>

    <div class="collapse navbar-collapse" id="navContent">
        <ul class="navbar-nav mr-auto">
            <?php if(!$token_valid): ?>
            <li class="nav-item">
                <a href="<?= SITE_PREFIX ?>/login" class="nav-link">Log In</a>
            </li>
            <?php else: ?>
                <?php if($current_user->getPermLevel() == 9): ?>
                    <li class="nav-item">
                        <a href="<?= SITE_PREFIX ?>/admin" class="nav-link">Admin</a>
                    </li>
                <?php endif ?>
                <li class="nav-item">
                    <a href="<?= SITE_PREFIX ?>/entity/create" class="nav-link">Create Entity</a>
                </li>
                <li class="nav-item">
                    <a href="<?= SITE_PREFIX ?>/logout" class="nav-link">Logout</a>
                </li>
            <?php endif ?>
            <?php startblock('navbar') ?>
            <?php endblock() ?>
        </ul>

        <form class="form-inline my-2 my-lg-0" action="<?= SITE_PREFIX ?>/search" method="get">
            <input class="form-control mr-sm-2" type="text" name="query" placeholder="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>
<?php
    startblock('body');
    endblock();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
<?php
    startblock('script');
    endblock();
?>
</body>
</html>