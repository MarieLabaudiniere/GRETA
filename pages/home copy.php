<?php
include('./utils/db.php');
include('./fonctions/materielUse.php');
?>
<p class="lead text-center font-weight-bold">Recherche d'un matériel</p>
<div class="container mt-3">
    <!--<form action="index.php?page=home" method="post" id="formSearchResa">-->
    <div class="row">
        <div class="col">
            <select id="cat_materiel" name="cat_materiel" onchange="requestSelect(this);" class="form-control">
                <option selected></option>
                <?php
                echo generateOptionCatMatHTML($pdo);
                ?>
            </select>
        </div>
        <div class="col">
            <select id="type_materiel" name="type_materiel" class="form-control">
                <!--<option selected></option>-->
                <!--les options seront générées automatiquement avec l'Ajax-->
            </select>
        </div>
        <div class="col">
            <input type="text" id="libelle_mat" name="libelle_mat" class="form-control" placeholder="Libelle matériel" value="">
        </div>
        <div class="row">
            <div class="col">
                <button id="search-advance" class="btn btn-secondary" onclick="requestSearch();">Rechercher</button>
            </div>
        </div>
        <!--</form>-->
    </div>

    <script>
        //génération de la requête HTTP qui va dynamiquement charger le contenu
        //de la liste déroulante correspondant au type de matériel et qui renvoie les données en HTML
        function requestSelect(oSelect) {
            //const oSelect = document.querySelector('#cat_materiel');
            const value = oSelect.options[oSelect.selectedIndex].value;
            //en JQuery
            //const value = $('#cat-materiel option:selected').val();
            console.log("valeur du select " + value);
            const xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    readDataSelect(xhr.responseText);
                }
            };

            xhr.open("GET", "request/listTypeMat.php?idCat=" + value, true);
            xhr.send();
        }
        
        
        //fonction appeler dans le cas où le serveur à envoyer un retour positif
        //pour la requête listTypeMat
        function readDataSelect(oData) {
            const oSelect = document.getElementById("type_materiel");
            oSelect.innerHTML = oData;
            //JQ
            //$('#type-materiel').html(oData);
        }
    </script>