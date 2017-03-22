<?php
    require_once 'data/token.php';

    if(!$token_valid) {
        header('Location: /');
        exit;
    }

    $ents = getEntities(250, 0, true);

if($_POST) {
        $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $published = trim(filter_input(INPUT_POST, 'published', FILTER_VALIDATE_BOOLEAN));
        $parent = trim(filter_input(INPUT_POST, 'parent', FILTER_SANITIZE_NUMBER_INT));
        $editId = filter_input(INPUT_POST, 'editid', FILTER_SANITIZE_NUMBER_INT);

        $errName = $name == null || strlen($name) < ENTITY_NAME_MIN_LENGTH || strlen($name) > ENTITY_NAME_MAX_LENGTH;
        $errDescription = $description == null || strlen($description) < DESCRIPTION_MIN_LENGTH;

        $newEntity = new Entity();
        $newEntity->setId($editId);
        $newEntity->setName($name);
        $newEntity->setDescription($description);
        $newEntity->setPublished($published);
        $newEntity->setParent($parent);

        if($editId == null) {
            $result = putEntity($newEntity);
        } else {
            $result = editEntity($newEntity);
        }

        if ($result != null) {
            $edit = new Edit();
            $edit->setEntityId($result);
            $edit->setUserId($current_user->getId());
            putEditEntry($edit);
            header('Location: /entity/' . urlencode($newEntity->getName()));
            exit;
        }
    } else if(isset($_GET['editid'])) {
        $editId = filter_input(INPUT_GET, 'editid', FILTER_SANITIZE_NUMBER_INT);
        $entity = getEntity($editId);
        $name = $entity->getName();
        $description = $entity->getDescription();
        $published = $entity->isPublished();
        $parent = $entity->getParent();
    }
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?><?= isset($editId) ? 'Edit Entity' : 'Create Entity' ?><?php endblock() ?>

<?php startblock('body') ?>
<link rel="stylesheet" href="/css/select2.min.css">

<div class="container mt-4">
    <?php if(isset($editId)): ?>
        <h4>Edit <?= $entity->getName() ?></h4>
    <?php else: ?>
        <h4>Create Entity</h4>
    <?php endif; ?>
    <div class="card">
        <div class="card-block">
            <form action="/entity/create" method="post">
                <?php if(isset($editId)): ?>
                    <input type="hidden" name="editid" value="<?= $editId ?>">
                <?php endif; ?>
                <fieldset class="form-group <?= $errName ? 'has-danger' : '' ?>">
                    <label for="in-name" class="form-control-label">Name</label>
                    <input id="in-name" type="text" class="form-control" name="name" value="<?= isset($name) ? $name : '' ?>">
                    <?php if(isset($errName) && $errName == true): ?>
                        <span class="form-control-feedback">The name must be between <?= ENTITY_NAME_MIN_LENGTH ?> and <?= ENTITY_NAME_MAX_LENGTH ?> characters long</span>
                    <?php endif ?>
                </fieldset>
                <fieldset class="form-group <?= $errDescription ? 'has-danger' : '' ?>">
                    <label for="in-description" class="form-control-label">Description</label>
                    <textarea id="in-description" class="form-control" name="description" rows="10"><?= isset($description) ? $description : '' ?></textarea>
                    <?php if(isset($errDescription) && $errDescription == true): ?>
                        <span class="form-control-feedback">The description must be more than <?= DESCRIPTION_MIN_LENGTH ?> characters in length</span>
                    <?php endif ?>
                </fieldset>
                <fieldset class="form-group">
                    <label for="in-published" class="form-control-label">
                        <input id="in-published" name="published" type="checkbox" <?= isset($published) ? $published ? 'checked' : '' : '' ?>>
                        Published
                    </label>
                </fieldset>
                <fieldset class="form-group">
                    <label for="in-parent" class="form-control-label">Parent</label>
                    <select id="in-parent" class="form-control" name="parent">
                        <option value="">None</option>
                        <?php foreach ($ents as $entity): ?>
                            <option value="<?= $entity->getId() ?>"><?= $entity->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>
                <button class="btn btn-primary" type="submit"><?= isset($editId) ? 'Edit' : 'Create' ?></button>
                <?php if (isset($editId)): ?>
                    <button type="button" class="btn btn-danger">Delete</button>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>
<?php endblock() ?>
<?php startblock('script') ?>
    <script src="/js/select2.full.min.js"></script>
    <?php if(isset($parent)): ?>
        <script>
            $('#in-parent').val(<?= $parent ?>);
        </script>
    <?php endif; ?>
    <script>
        $('#in-parent').select2({
            placeholder: "Select a parent (optional)",
            allowClear: true
        });
    </script>
<?php endblock() ?>
