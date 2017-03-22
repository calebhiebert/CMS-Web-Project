<?php
require_once 'token.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$ent = getEntity($id);

if($ent != null && ($ent->isPublished() || $token_valid)) {
    $ent->setTags(getEntityTags($id));
    $parents = array($ent);
    $inheritedTags = array();
    $created = getEntityCreation($ent->getId());
    $edited = getEntityLastEdit($ent->getId());

    $editString = 'Created ' . prettyTime($created->getTime()) . ($created != $edited ? ' by ' . $created->getUsername() . '. Last edited ' . prettyTime($edited->getTime()) . ' by ' . $edited->getUsername() . '.' : '.');

    populateParentTree($ent);

    if($token_valid) {
        populateChildren($ent);
    } else {
        populatePublicChildren($ent);
    }

    while ($parents[0]->getParent() != null) {
        array_unshift($parents, $parents[0]->getParent());

        foreach ($parents[0]->getTags() as $tag) {
            array_unshift($inheritedTags, $tag);
        }
    }

    if($ent->getParent() != null) {
        populateChildren($ent->getParent());
    }

} else {
    header('Location: /');
    exit;
}
?>

<?php include 'base.php' ?>
<?php startblock('title') ?>Main Page<?php endblock() ?>

<?php startblock('body') ?>
<div class="container mt-3">
    <?php if($ent): ?>
        <nav class="breadcrumb">
            <?php foreach ($parents as $entity): ?>
                <?php if($entity === end($parents)): ?>
                    <span class="breadcrumb-item active"><?= $entity->getName() ?></span>
                <?php else: ?>
                    <a class="breadcrumb-item" href="<?= '/entity/' . $entity->getId() ?>"><?= $entity->getName() ?></a>
                <?php endif ?>
            <?php endforeach ?>
        </nav>
        <?php if(!$ent->isPublished()): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                This page is not visible to the public!
                You are able to view this page because you are logged in.
            </div>
        <?php endif ?>
        <div class="row">
            <div class="col-md mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?= $ent->getName() ?></h5>
                    </div>
                    <div class="card-block">
                        <p class="card-text"><?= nl2br($ent->getDescription()) ?></p>
                        <?php if($token_valid): ?>
                            <a class="card-link" href="/entity/<?= $ent->getId() ?>/edit">Edit</a>
                        <?php endif ?>
                    </div>
                    <small class="card-footer text-muted p-2">
                        <?= $editString ?>
                    </small>
                </div>
            </div>
            <div class="col-md-4">
                <?php if (count($inheritedTags) != 0 && count($ent->getTags()) != 0): ?>
                    <div class="col-md-auto mb-3">
                        <div class="card">
                            <h6 class="card-header">Data</h6>
                            <div class="list-group list-group-flush">
                                <?php foreach ($inheritedTags as $tag): ?>
                                    <a class="list-group-item text-muted" href="/tag/<?= urlencode($tag->getTagName()) ?>/<?= urlencode($tag->getTagData()) ?>"><strong><?= $tag->getTagName() ?></strong>: <?= $tag->getTagData() ?></a>
                                <?php endforeach ?>
                                <?php foreach ($ent->getTags() as $tag): ?>
                                    <a class="list-group-item" href="/tag/<?= urlencode($tag->getTagName()) ?>/<?= urlencode($tag->getTagData()) ?>"><strong><?= $tag->getTagName() ?></strong>: <?= $tag->getTagData() ?></a>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <?php if(count($ent->getChildren()) > 0 && DISPLAY_CHILDREN): ?>
                    <div class="col-md-auto mb-3">
                        <div class="card">
                            <h6 class="card-header">Children</h6>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($ent->getChildren() as $child): ?>
                                    <a href="/entity/<?= $child->getId() ?>" class="list-group-item"><?= $child->getName() ?></a>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                <?php endif ?>
                <?php if($ent->getParent() != null && count($ent->getParent()->getChildren()) - 1 > 0 && DISPLAY_SIBLINGS): ?>
                    <div class="col-md-auto mb-3">
                        <div class="card">
                            <h6 class="card-header">Siblings</h6>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($ent->getParent()->getChildren() as $sibling): ?>
                                    <?php if($sibling->getId() != $ent->getId() && $sibling->isPublished()): ?>
                                        <a href="/entity/<?= $sibling->getId() ?>" class="list-group-item"><?= $sibling->getName() ?></a>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    <?php else: ?>
        The entity was not found :(
    <?php endif ?>
</div>
<?php endblock() ?>

