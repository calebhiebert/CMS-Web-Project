<?php
require_once 'data/token.php';

if(!$token_valid) {
    redirect();
    exit;
}

$ents = getEntities(250, 0, true);

$tagArr = [];

if($_POST) {
    $name = trim(str_replace(DISALLOWED_NAME_CHARS, '', filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
    $description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $published = trim(filter_input(INPUT_POST, 'published', FILTER_VALIDATE_BOOLEAN));
    $parent = trim(filter_input(INPUT_POST, 'parent', FILTER_SANITIZE_NUMBER_INT));
    $tagInput = isset($_POST['tags']) ? $_POST['tags'] : [];
    $editId = filter_input(INPUT_POST, 'editid', FILTER_SANITIZE_NUMBER_INT);

    $errName = $name == null || strlen($name) < ENTITY_NAME_MIN_LENGTH || strlen($name) > ENTITY_NAME_MAX_LENGTH;
    $errDescription = $description == null || strlen($description) < DESCRIPTION_MIN_LENGTH;

    foreach ($tagInput as $tag) {
        array_push($tagArr, filter_var($tag, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }

    if(isset($_GET['editid'])) {
        $editId = filter_input(INPUT_GET, 'editid', FILTER_SANITIZE_NUMBER_INT);
        $entity = getEntity($editId);
        
        if($entity == null) {
            redirect();
            exit;
        }
    }

    if(!$errName && !$errDescription) {
        $newEntity = new Entity();
        $newEntity->setId($editId);
        $newEntity->setName($name);
        $newEntity->setDescription($description);
        $newEntity->setPublished($published);
        $newEntity->setParent($parent);

        if ($editId == null) {
            $result = putEntity($newEntity);
        } else {
            $result = editEntity($newEntity);
        }

        if ($result != null) {
            putTags($tagInput, $result);
            $edit = new Edit();
            $edit->setEntityId($result);
            $edit->setUserId($current_user->getId());
            putEditEntry($edit);
            redirect('/entity/'.urlencode($newEntity->getName()));
            exit;
        }
    }

} else if(isset($_GET['editid'])) {
    $editId = filter_input(INPUT_GET, 'editid', FILTER_SANITIZE_NUMBER_INT);
    $entity = getEntity($editId);

    if($entity == null) {
        redirect();
        exit;
    }

    $entity->setTags(getEntityTags($editId));
    $name = $entity->getName();
    $description = $entity->getDescription();
    $published = $entity->isPublished();
    $parent = $entity->getParent();

    foreach ($entity->getTags() as $tag) {
        array_push($tagArr, $tag->getTag());
    }
} else if (isset($_GET['delete']) && isset($_GET['id'])) {
    $entityId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $imgs = getEntityImages($entityId);
    deleteEntity($entityId);

    foreach ($imgs as $img) {
        deleteImageFile($img->getId(), $img->getFileExt());
    }

    redirect();
    exit;
}
?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?><?= isset($editId) ? 'Edit Entity' : 'Create Entity' ?><?php endblock() ?>

<?php startblock('style') ?>
    <link href="<?= SITE_PREFIX ?>/css/froala_editor.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE_PREFIX ?>/css/froala_style.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE_PREFIX ?>/css/plugins/colors.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE_PREFIX ?>/css/plugins/emoticons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE_PREFIX ?>/css/plugins/line_breaker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE_PREFIX ?>/css/plugins/quick_insert.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE_PREFIX ?>/css/plugins/special_characters.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE_PREFIX ?>/css/plugins/table.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="<?= SITE_PREFIX ?>/css/select2.min.css">
<?php endblock() ?>

<?php startblock('body') ?>
<div class="container mt-4">
    <?php if(isset($editId)): ?>
        <h4>Edit <?= $entity->getName() ?></h4>
    <?php else: ?>
        <h4>Create Entity</h4>
    <?php endif; ?>
    <div class="card">
        <div class="card-block">
            <form action="<?= SITE_PREFIX ?><?= isset($editId) ? '/entity/'.$editId.'/edit' : '/entity/create' ?>" method="post">
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
                <fieldset class="form-group">
                    <label for="tags"></label>
                    <select id="tags" class="form-control" name="tags[]" multiple="multiple">
                        <?php foreach ($tagArr as $tag): ?>
                            <option value="<?= $tag ?>" selected><?= $tag ?></option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>
                <button class="btn btn-primary" type="submit"><?= isset($editId) ? 'Save' : 'Create' ?></button>
                <?php if (isset($editId)): ?>
                    <a href="<?= SITE_PREFIX ?>/entity/<?= $editId ?>/delete" class="btn btn-danger">Delete</a>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>
<?php endblock() ?>
<?php startblock('script') ?>
    <script src="<?= SITE_PREFIX ?>/js/select2.full.min.js"></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/froala_editor.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/align.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/colors.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/emoticons.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/font_family.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/font_size.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/line_breaker.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/link.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/lists.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/paragraph_format.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/paragraph_style.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/quick_insert.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/quote.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/special_characters.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/table.min.js'></script>
    <script type='text/javascript' src='<?= SITE_PREFIX ?>/js/plugins/url.min.js'></script>
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

        $('#tags').select2({
            tags: true
        });

        $('#in-description').froalaEditor({
            toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'specialCharacters', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', '-', 'quote', 'insertHR', 'insertLink', 'insertTable', '|', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html', 'applyFormat', 'removeFormat', 'fullscreen', 'help']
        });
    </script>
<?php endblock() ?>
