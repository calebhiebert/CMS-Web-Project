<?php
/**
 * Allows users to search entities
 */

require_once "token.php";
require_once 'db/crud.php';
require_once "db.php";

$entities = getEntities(9, 0);
?>

<?php include 'base.php' ?>
<?php startblock('title') ?>Entity Index<?php endblock() ?>

<?php startblock('body') ?>
    <div class="container mt-4" id="entity-list">
        <ul class="list-group">
            <li class="list-group-item">

            </li>
        </ul>
        <nav>
            <ul class="pagination">
                <li class="page-item" v-for=""></li>
            </ul>
        </nav>
    </div>
<?php endblock() ?>

<?php startblock('script') ?>
<script src="https://vuejs.org/js/vue.js"></script>
<script>
    var page = 0;

    var app = new Vue({
        el: '#entity-list',
        data: {
            entities: []
        }
    });
</script>
<?php endblock() ?>
