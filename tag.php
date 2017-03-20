<?php

require_once 'token.php';
require_once 'db/crud.php';

$tagData = strtoupper(trim(filter_input(INPUT_GET, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
$tagName = strtoupper(trim(filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));

$tag = getTag($tagName, $tagData);
$tags = getTagsByName($tagName);

if($tag == null) {
    header('Location: /');
    exit;
}

?>

<?php include 'base.php' ?>
<?php startblock('title') ?>Tag: <?= $tag->getTagData() ?><?php endblock() ?>

<?php startblock('body') ?>
<div class="container mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><?= $tag->getTagData() ?></h5>
            <span class="card-subtitle mb-0"><?= $tag->getTagName() ?></span>
        </div>
        <div class="card-block"><?= $tag->getDescription() != null ? nl2br($tag->getDescription()) : 'This tag does not have any description' ?></div>
    </div>
    <?php if(count($tags) > 1): ?>
        <div class="card mt-3">
            <h5 class="card-header">Other <?= $tag->getTagName() ?></h5>
            <ul class="list-group list-group-flush">
                <?php foreach ($tags as $tag): ?>
                    <li class="list-group-item"><?= $tag->getTagData() ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif ?>
</div>
<?php endblock() ?>
