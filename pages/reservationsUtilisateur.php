<?php
//page d'accueil
//chargement des paramètres de la BD et connexion
include('./utils/db.php');
include('./fonctions/reservationUse.php');

try {
    echo '<p class="lead text-center font-weight-bold">Vos réservations en cours</p>';
    $results = getListResaEnCours($pdo);
    echo "<div class=\"container mt-3\"><table class=\"table\">
    <thead class=\"thead-light\">
        <tr>
            <th>Libellé mat</th>
            <th>Marque</th>
            <th>Début réservation</th>
            <th>Fin réservation</th>
        </tr>
    </thead>
    <tbody>";
    foreach($results AS $result){
        echo "<tr>";
        echo "<td>" . $result['libMat'] . "</td>";
        echo "<td>" . $result['libMarq'] . "</td>";
        echo "<td>" . formatDateBD($result['DATE_DEBUT_RESA']) . "</td>";
        echo "<td>" . formatDateBD($result['DATE_FIN_RESA']) . "</td>";
        echo "<tr>";
    }
    echo "</tbody>
    </table>
    </div>";

    echo '<p class="lead text-center font-weight-bold">Vos futurs réservations</p>';
    $results = getListFuturResa($pdo);
    echo "<div class=\"container mt-3\"><table class=\"table\">
    <thead class=\"thead-light\">
        <tr>
            <th>Libellé mat</th>
            <th>Marque</th>
            <th>Début réservation</th>
            <th>Fin réservation</th>
        </tr>
    </thead>
    <tbody>";
    foreach($results AS $result){
        echo "<tr>";
        echo "<td>" . $result['libMat'] . "</td>";
        echo "<td>" . $result['libMarq'] . "</td>";
        echo "<td>" . formatDateBD($result['DATE_DEBUT_RESA']) . "</td>";
        echo "<td>" . formatDateBD($result['DATE_FIN_RESA']) . "</td>";
        echo "<tr>";
    }
    echo "</tbody>
    </table>
    </div>";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
