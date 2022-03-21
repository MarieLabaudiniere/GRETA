<?php
include('../utils/db.php');
include('../fonctions/materielUse.php');
//affichage du résultat sur le matériel
$results = getListMateriel($pdo, $_GET);
if (is_null($results) || count($results) == 0) {
    echo "<p>pas de résultat</p>";
} else {
    echo "<table class=\"table\">
            <thead class=\"thead-light\">
                <tr>
                <th>Libellé mat</th>
                <th>Marque</th>
                <th>Type mat</th>
                <th>Réservation</th>
                </tr>
            </thead>
            <tbody>";
            foreach ($results as $result) {
                $idMat = $result['ID_MAT'];
                $libelleMat = $result['LIBELLE_MAT'];
                echo "<tr>";
                echo "<td>" . $libelleMat . "</td>";
                echo "<td>". $result['LIB_MARQ'] . "</td>";
                echo "<td>". $result['LIB_TYPEMAT'] . "</td>";
                echo '<td><a href="index.php?page=reservationCalendar&id_mat=' . $idMat . 
                '&libelle_mat='.$libelleMat.'"><img src="./public/medias/calendar.svg"></a></td>';
                echo "<tr>";
            }
            echo "</table>";
}
