<?php

require_once 'data/token.php';

$tagName = strtoupper(trim(filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));

echo $tagName;

$tags = getTagsByName($tagName);

if($tags == null) {
    header('Location: /');
    exit;
}

?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Tags: <?= $tagName ?><?php endblock() ?>

<?php startblock('body') ?>
<div class="container mt-3">
    <div class="card">
        <h5 class="card-header"><?= $tagName ?></h5>
        <ul class="list-group">
            <?php foreach ($tags as $tag): ?>
                <li class="list-group-item"><?= $tag->getTagData() ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endblock() ?>
