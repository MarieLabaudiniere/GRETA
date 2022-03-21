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
                    <option selected></option>
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
    function requestSelect() {
        const oSelect = document.querySelector('#cat_materiel');
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

    function readDataSelect(oData) {
        const oSelect = document.getElementById("type_materiel");
        oSelect.innerHTML = oData;
        //JQ
        //$('#type-materiel').html(oData);
    }

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

    function readDataSearch(oData) {
        console.log("écriture du résultat");
        console.log(oData);
        const divResult = document.querySelector("#result");
        if(divResult!=null) {
            document.body.removeChild(divResult);
        }
        const div = document.createElement('div');
        div.setAttribute('id','result');
        div.classList.add("container", "mt-3");
        div.innerHTML = oData;
        document.body.appendChild(div);
        //en JQuery
        //$(document).append(oData);
    }
</script>