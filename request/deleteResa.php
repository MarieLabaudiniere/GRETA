<?php
include('../utils/db.php');
include('../fonctions/reservationUse.php');
try {
    deleteResa($pdo, $_POST['id_resa']);
} catch (PDOException $e) {
     echo $e;
}
?>
