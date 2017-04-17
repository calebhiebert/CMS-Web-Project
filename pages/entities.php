<?php
/**
 * Page for displaying entities in a sortable table
 */
require_once 'data/token.php';

if(!$token_valid) {
    redirect();
    exit;
}

$entities = getMultiple(
        "SELECT Entities.Id entid, Entities.Name, Entities.Parent prnt, COALESCE((SELECT Name FROM Entities WHERE Id = prnt), 'N/A') AS Parent,
                                   (SELECT MAX(Time) FROM EditLog WHERE EntityId = entid) AS LastEdit,
                                   (SELECT MIN(Time) FROM EditLog WHERE EntityId = entid) AS Created
          FROM Entities;", []
)

?>

<?php include 'data/base.php' ?>
<?php startblock('title') ?>Pages<?php endblock() ?>

<?php startblock('navbar') ?>
<li class="nav-item">
    <a href="" class="nav-link active">Entity List</a>
</li>
<?php endblock() ?>

<?php startblock('body') ?>
<link rel="stylesheet" type="text/css" href="<?= SITE_PREFIX ?>/css/theme.default.min.css"/>
<div class="container mt-3">
    <h4>Entities</h4>
    <table id="entities-table" class="table table-sm table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Parent</th>
            <th>Last Edited</th>
            <th>Created On</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($entities as $ent): ?>
            <tr>
                <td><a href="#"><?= $ent['Name'] ?></a></td>
                <td><?= $ent['Parent'] ?></td>
                <td><?= $ent['LastEdit'] ?></td>
                <td><?= $ent['Created'] ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endblock() ?>
<?php startblock('script') ?>
<script type="text/javascript" src="<?= SITE_PREFIX ?>/js/jquery.tablesorter.js"></script>
<script>
    $(document).ready(function() {
        $('#entities-table').tablesorter();
    });
</script>
<?php endblock() ?>
