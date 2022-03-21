<?php
include('../utils/db.php');
include('../fonctions/materielUse.php');
echo generateOptionTypeMatHTML($pdo, $_GET['idCat']);
?>