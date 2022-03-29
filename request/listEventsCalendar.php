<?php
    include('../utils/db.php');
    include('../fonctions/reservationUse.php');
    $idMat = $_GET['idMat'];
    $debut = $_GET['start'];
    $fin = $_GET['end'];
    $idUtil = $_GET['idUtil'];
    echo json_encode(getEvenementsResa($pdo, $idMat, $debut, $fin, $idUtil));
?>