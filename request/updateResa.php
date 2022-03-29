<?php
include('../utils/db.php');
include('../fonctions/reservationUse.php');
try {
    updateResa($pdo, $_POST['libelle_resa'], $_POST['id_resa']);
} catch (PDOException $e) {
     echo $e;
}
?>