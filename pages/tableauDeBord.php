<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<!-- component template -->
<script type="text/x-template" id="tableau-template">
    <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th v-for="key in columns">
              {{ key }}
            </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in categories">
            <td v-for="key in columns">
              <span v-html="entry[key]"></span>
            </td>
            <td><a href="#" v-on:click="suppression(entry)">
                <img src="public/medias/trash.svg"></a></td>
          </tr>
        </tbody>
      </table>
</script>
<div id="app">
    <div class="container mt-3 border border-secondary rounded">
        <input class="m-5" type="text" id="lib" placeholder="libellé">
        <button id="ajout" class="btn btn-dark" v-on:click="ajout()">Ajouter</button>
    </div>

    <div class="container mt-3">
        <tableau :categories="gridData" :columns="gridColumns">
        </tableau>
    </div>
</div>
<script>
    // déclaration du composant tableau
    Vue.component("tableau", {
        template: "#tableau-template",
        props: {
            categories: Array,
            columns: Array
        },
        methods: {
            suppression: function(elem) {
                const index = this.categories.indexOf(elem);
                this.categories.splice(index, 1);
            }
        }
    });

    // création de la vue
    const app = new Vue({
        el: "#app",
        data: {
            gridColumns: ["libelle", "date"],
            gridData: []
        },
        methods: {
            ajout: function() {
                const lib = $('#lib').val();
                const dateHeureSt = new Date().toLocaleString();
                this.gridData.push({
                    libelle: lib,
                    date: dateHeureSt
                });
            }
        }
    });
</script>