<?php
include('./utils/db.php');
include('./fonctions/materielUse.php');
?>
<p class="lead text-center font-weight-bold">Recherche d'un matériel</p>
<div class="container mt-3">
    <!--<form action="index.php?page=home" method="post" id="formSearchResa">-->
    <div class="row">
        <div class="col">
            <select id="cat_materiel" name="cat_materiel" onchange="requestSelectAjaxLoad();" class="form-control">
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
        //génération de la requête HTTP qui va dynamiquement charger le contenu
        //de la liste déroulante correspondant au type de matériel et qui renvoie les données en HTML
        function requestSelectJSON(oSelect) {
            //const oSelect = document.querySelector('#cat_materiel');
            const value = oSelect.options[oSelect.selectedIndex].value;
            //en JQuery
            //const value = $('#cat_materiel option:selected').val();
            console.log("valeur du select " + value);
            const xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    readDataSelectJSON(xhr.responseText);
                }
            };

            xhr.open("GET", "request/listTypeMatJSON.php?idCat=" + value, true);
            xhr.send();
        }

        function requestSelectAjax() {
            const value = $('#cat_materiel option:selected').val();
            const request = $.ajax({
                //L'URL de la requête 
                url: "request/listTypeMatJSON.php",
                //La méthode d'envoi (type de requête)
                method: "GET",
                //Le format de réponse attendu
                dataType: "json",
                data: "idCat=" + value
            });
            //Ce code sera exécuté en cas de succès - La réponse du serveur est passée à done()
            request.done(function(response) {
                $select = $("#type_materiel");
                //vider le contenu du select pour éviter de cumuler le résulat d'autres requête HTTP
                $select.empty();
                //parcours des éléments de la liste réponse
                response.forEach(function(item) {
                    //ajout de l'option dans le select correspondant à la réponse
                    $select.append("<option value=" + item.ID_TYPE_MAT + ">" 
                    + item.LIBELLE_TYPE_MAT + "</option>")
                });
            });
            //Ce code sera exécuté en cas d'échec
            request.fail(function(error) {
                console.log("erreur");
                console.log(JSON.stringify(error));
            });
        }
        function requestSelectAjaxLoad(){
            const value = $('#cat_materiel option:selected').val();
            $("#type_materiel").load("request/listTypeMat.php?idCat=" + value);
        }

        //fonction appeler dans le cas où le serveur à envoyer un retour positif
        //pour la requête listTypeMat
        function readDataSelect(oData) {
            const oSelect = document.getElementById("type_materiel");
            oSelect.innerHTML = oData;
            //JQ
            //$('#type-materiel').html(oData);
        }
        //fonction appeler dans le cas où le serveur à envoyer un retour positif
        //pour la requête listTypeMat
        function readDataSelectJSON(oData) {
            const oSelect = document.getElementById("type_materiel");
            while (oSelect.firstChild) { //suppression de toutes les options existantes sur le select sinon accumulation de celle-ci
                oSelect.removeChild(oSelect.firstChild);
            }
            //la réponse est du texte, il faut donc analyser ce texte au format JSON pour en créer un objet (tableau) 
            const objetJSON = JSON.parse(oData);
            const nbOption = objetJSON.length;
            for (let i = 0; i < nbOption; i++) {
                const optionJSON = objetJSON[i];
                const option = document.createElement('option');
                option.setAttribute("value", optionJSON.ID_TYPE_MAT);
                option.textContent = optionJSON.LIBELLE_TYPE_MAT;
                oSelect.appendChild(option);
            }
        }
        //génération de la requête HTTP qui va dynamiquement construire le tableau
        //de résultats
        function requestSearch() {
            oSelect = document.querySelector('#type_materiel');
            const valueTypeMat = oSelect.options[oSelect.selectedIndex].value;
            const valueLib = document.querySelector('#libelle_mat').value;
            //en JQuery
            // const valueTypeMat = $('#type-materiel').val();
            // const valueLib = $('#libelle_mat').val();
            console.log("valeur type mat " + valueTypeMat);
            console.log("valeur libellé " + valueLib);
            const xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log("success");
                    readDataSearch(xhr.responseText);
                }
            };

            xhr.open("GET", "request/tabMat.php?type_materiel=" + valueTypeMat + "&libelle_mat=" + valueLib, true);
            xhr.send();
        }
        //fonction appeler dans le cas où le serveur à envoyer un retour positif
        //pour la requête tabMat
        function readDataSearch(oData) {
            console.log("écriture du résultat");
            console.log(oData);
            const divResult = document.querySelector("#result");
            if (divResult != null) {
                document.body.removeChild(divResult);
            }
            const div = document.createElement('div');
            div.setAttribute('id', 'result');
            div.classList.add("container", "mt-3");
            div.innerHTML = oData;
            document.body.appendChild(div);
            //en JQuery
            //$(document).append(oData);
        }
    </script>