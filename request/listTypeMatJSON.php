<?php
include('../utils/db.php');
include('../fonctions/materielUse.php');
echo json_encode(getListTypeMatJSON($pdo, $_GET['idCat']));
?>