<?php
include('./utils/db.php');
include('./fonctions/reservationUse.php');
include('./fonctions/marqueUse.php');

?>

<!-- Pop-up -->
<div id="popup" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5> Nouvelle Réservation </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?page=reservation" method="post" id="creation-resa">
                    <div class="mt-3">
                        <input id="popIdMat" name="id_mat_resa" type="text" value="" readonly>
                    </div>
                    <div class="mt-3">
                        <input id="popLibelleResa" name="libelle_resa" type="text" value="" readonly>
                    </div>
                    <div class="mt-3">
                        <input id="popDateDeb" name="date_debut_resa" type="date" value="" required>
                    </div>
                    <div class="mt-3">
                        <input id="popDateFin" name="date_fin_resa" type="date" value="" required>
                    </div>
                    <div class="mt-3">
                        <button name="creationResa" type="submit" class="btn" onclick="controlPeriode('popDateDeb', 'popDateFin')">Réserver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--recherche rapide-->
<div class="container">
    <div class="row">
        <div class="span12">
            <form action="index.php?page=reservation" method="post" id="custom-search-form" class="form-search form-horizontal pull-right">
                <div class="input-append span12">
                    <input name="fastSearchValue" type="text" class="search-query" placeholder="Search">
                    <button name="fastSearch" type="submit" class="btn"><img src="./public/medias/search.svg"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--recherche avancée-->
<p class="lead text-center font-weight-bold">Recherche du matériel disponible</p>
<div class="container mt-3">
    <form action="index.php?page=reservation" method="post" id="formSearchResa">
        <div class="row">
            <div class="col">
                <select id="marque" name="marque" class="form-control">
                    <option selected></option>
                    <?php
                    $options = generateOptionHTML($pdo);
                    foreach ($options as $option) {
                        echo $option;
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <input type="text" name="libelle_mat" class="form-control" placeholder="Libelle matériel" value="<?php echo @$_POST['libelle_mat'] ?>">
            </div>
            <div class="col">
                <input type="date" id="date_debut_resa" name="date_debut_resa" class="form-control" placeholder="" value="<?php echo @$_POST['date_debut_resa'] ?>" required>
            </div>
            <div class="col">
                <input type="date" id="date_fin_resa" name="date_fin_resa" class="form-control" placeholder="" value="<?php echo @$_POST['date_fin_resa'] ?>" required>
            </div>
            <div class="row">
                <div class="col">
                    <input type="submit" name="searchAdvance" id="search-advance" 
                    onclick="controlPeriode('date_debut_resa', 'date_fin_resa')" tabindex="4" class="form-control btn-secondary" value="Rechercher">
                </div>
            </div>
    </form>
</div>
<?php
//CAS DE LA CREATION D'UNE RESA
if(isset($_POST['creationResa'])) {
    if(createResa($pdo, $_POST)){ //la création en BD s'est bien passé
        echo "<p>votre réservation a bien été prise en compte</p>";
    } else {
        echo "<p>Une erreur a bloqué la prise en compte de votre réseravtion, veuillez recommencer</p>";
    }
}
//CAS DE LA RECHERCHE RAPIDE
if (isset($_POST['fastSearch'])) {
    if (isset($_POST['fastSearchValue'])) {
        //affichage du résultat de la recherche rapide
        $results = getListMaterielDispoFast($pdo, $_POST['fastSearchValue']);
        if (is_null($results) || count($results) == 0) {
            echo "<p>pas de résultat</p>";
        } else {
            echo "<div class=\"container mt-3\"><table class=\"table\">
                    <thead class=\"thead-light\">
                        <tr>
                        <th>Libellé mat</th>
                        <th>Réserver</th>
                        </tr>
                        </thead>
                    <tbody>";
            foreach ($results as $result) {
                echo "<tr>";
                echo "<td>" . $result['LIBELLE_MAT'] . "</td>";
                echo '<td><a href="#" onclick="reportData(' . $result['ID_MAT'] . ',\''
                . $result['LIBELLE_MAT'] . '\',\'\',\'\')" data-toggle="modal" data-target="#popup"><img src="./public/medias/calendar-check.svg"></a></td>';
                echo "<tr>";
            }
            echo "</table></div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center mt-2' role='alert'>
        Vous devez saisir une chaine de caractères pour la recherche rapide</div>";
    }
}
//CAS DE LA RECHERCHE AVANCEE
if (isset($_POST['searchAdvance'])) {
    //print_r($_POST);
    $results = getListMaterielDispo($pdo, $_POST);
    if (is_null($results) || count($results) == 0) {
        echo '<div class="text-center m-2">pas de résultat</div>';
    } else {
        echo "<div class=\"container mt-3\"><table class=\"table\">
                    <thead class=\"thead-light\">
                        <tr>
                        <th>Libellé mat</th>
                        <th>Réserver</th>
                        </tr>
                        </thead>
                    <tbody>";
        foreach ($results as $result) {
            echo "<tr>";
            echo "<td>" . $result['LIBELLE_MAT'] . "</td>";
            echo '<td><a href="#" onclick="reportData(' . $result['ID_MAT'] . ',\''
                . $result['LIBELLE_MAT'] . '\',\'' . $_POST['date_debut_resa'] . '\',\''
                . $_POST['date_fin_resa'] . '\')" data-toggle="modal" data-target="#popup"><img src="./public/medias/calendar-check.svg"></a></td>';
            echo "<tr>";
        }
        echo "</table></div>";
    }
}
?>
<script>
    function reportData(idMat, libelleMat, dateDebutResa, dateFinResa) {
        const champId = document.getElementById('popIdMat');
        champId.value = idMat;
        const champLib = document.getElementById('popLibelleResa');
        champLib.value = libelleMat;
        const champDateDeb = document.getElementById('popDateDeb');
        champDateDeb.value = dateDebutResa;
        const champDateFin = document.getElementById('popDateFin');
        champDateFin.value = dateFinResa;
    }
</script>