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
        <label>
            Search
            <input type="text" class="form-control" v-model="query">
        </label>
        <ul class="list-group">
            <li class="list-group-item" v-for="entity in entities.entities"><a href="/entity/{{ entity.id }}">{{ entity.name }}</a></li>
        </ul>
        <button type="button" class="btn btn-outline-secondary" v-if="page > 0" @click="prevPage">Previous Page</button>
        Page: {{ page }}
        <button type="button" class="btn btn-outline-secondary" @click="nextPage">Next Page</button>
    </div>
<?php endblock() ?>

<?php startblock('script') ?>
<script src="https://vuejs.org/js/vue.js"></script>
<script>
    var app = new Vue({
        el: '#entity-list',
        data: {
            page: 0,
            query: '',
            entities: {}
        },
        watch: {
            query: function (newQuery) {
                getEntities(newQuery, this.page, 5);
            }
        },
        methods: {
            nextPage: function () {
                this.page ++;
                getEntities(this.query, this.page, 5);
            },
            prevPage: function () {
                this.page --;
                if(this.page < 0)
                    this.page = 0;

                getEntities(this.query, this.page, 5);
            }
        }
    });

    getEntities('', 0, 5);
    
    function getEntities(query, page, num) {
        var data = "query=" + encodeURIComponent(query) + '&page=' + encodeURIComponent(page) + '&num=' + encodeURIComponent(num);
        var xhr = new XMLHttpRequest();

        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === 4 && this.status == 200) {
                app.entities = JSON.parse(this.responseText);
            }
        });

        xhr.open("POST", "/api/entity-search");
        xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
        xhr.send(data);
    }
</script>
<?php endblock() ?>
