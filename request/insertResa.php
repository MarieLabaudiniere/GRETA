<?php
include('../utils/db.php');
include('../fonctions/reservationUse.php');
try {
    createResa($pdo, $_POST);
} catch (PDOException $e) {
     echo $e;
}
print_r($_POST);
