<?php
include('../utils/db.php');
include('../fonctions/reservationUse.php');
try {
    echo createResa($pdo, $_POST);
} catch (PDOException $e) {
     echo $e;
}

